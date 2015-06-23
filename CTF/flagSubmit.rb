#!/usr/bin/ruby
require 'date'
require 'curb'

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

file = File.new("flags", "r")
while (line = file.gets)
    line.strip!
    if checkFlag(line)
        puts "#{line} is a valid flag, submitting!"
        submitFlag(rot(line))
	    sleep(0.5)
    end
end
file.close
