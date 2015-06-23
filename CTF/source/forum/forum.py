import sqlite3
from contextlib import closing
from itertools import chain
import os
from flask import Flask
from flask import request
from flask import session
from flask import g
from flask import redirect
from flask import url_for
from flask import render_template
from flask import flash
from flask import abort

DATABASE = os.path.dirname(os.path.realpath(__file__)) + "/db/forum.db"
DEBUG = False
SECRET_KEY = "default key"

app = Flask(__name__)
app.config.from_object(__name__)


def init_db():
    with closing(connect_db()) as db:
        with app.open_resource('schema.sql', mode='r') as f:
            db.cursor().executescript(f.read())
        db.commit()


def connect_db():
    return sqlite3.connect(app.config['DATABASE'])


def flatten(list2d):
    """ Flatten one level of nesting """
    return chain.from_iterable(list2d)


def encrypt(plaintext):
    LOWER = "abcdefghijklmnopqrstuvwxyz"
    UPPER = "ABCDEFGHIJKLMNOPQRSTUVWXYZ"
    shift = 13
    result = ""
    
    for char in plaintext:
        if char.isupper():
            i = (UPPER.index(char) + shift) % 26
            result += UPPER[i]
        elif char.islower():
            i = (LOWER.index(char) + shift) % 26
            result += LOWER[i]
        else:
            result += char

    return result
    

@app.before_request
def before_request():
    g.db = connect_db()


@app.teardown_request
def teardown_request(exception):
    db = getattr(g, 'db', None)
    if db is not None:
        db.close()


@app.errorhandler(401)
def page_not_authorized(e):
    return render_template("401.html"), 401


@app.route("/")
def index():
    return render_template("forum.html")


@app.route("/forum")
def forum():
    return render_template("forum.html")


@app.route("/register", methods=["GET", "POST"])
def register():
    error = None
    
    if request.method == "POST":
        username = request.form["username"]
        password = request.form["password"]
        password_confirm = request.form["password_confirm"]
        
        query = g.db.execute("SELECT name FROM user WHERE name = ?",
                             [username])
        exist = query.fetchone()
        
        if not username:
            error = "Username must not be empty"
        elif not password == password_confirm:
            error = "Your passwords don't match"
        elif exist:
            error = "Username already exists"
        else:
            encrypted = encrypt(password)
            g.db.execute('INSERT INTO user (name, password) VALUES (?, ?)',
                         [username, encrypted])
            g.db.commit()
            flash("You were successfully registered")
            return redirect(url_for("login"))                        

    return render_template("register.html", error=error)


@app.route("/login", methods=["GET", "POST"])
def login():
    error = None
    
    if request.method == "POST":
        username = request.form["username"]
        encrypted = encrypt(request.form["password"])
        
        query = g.db.execute("SELECT name, password FROM user WHERE \
                             name = ? AND password = ?",
                             [username, encrypted])
        result = query.fetchone()
        if not result:
            error = "Wrong username or password"
        else:
            session["user_id"] = username
            flash("You were logged in")
            return redirect(url_for("index"))

    return render_template("login.html", error=error)


@app.route("/logout")
def logout():
    session.pop("user_id", None)
    flash("You were logged out")
    return redirect(url_for("index"))


@app.route("/search", methods=["GET", "POST"])
def search():
    if not session.get("user_id"):
        abort(401)

    if request.method == "POST":
        search = request.form["search"]
        query = g.db.execute("SELECT name FROM user WHERE name LIKE '%"
                             + search + "%'")
        result = list(flatten(query.fetchall()))     
        return render_template("search.html", users=result)
    
    return render_template("search.html")


@app.route("/details")
def details():
    if not session.get("user_id"):
        abort(401)

    username = session.get("user_id")
    query = g.db.execute("SELECT name, password FROM user WHERE name = ?",
                         [username])
    result = query.fetchone()
    user = [result[0], encrypt(result[1])]

    return render_template("details.html", user=user)


if __name__ == '__main__':
    app.run()
