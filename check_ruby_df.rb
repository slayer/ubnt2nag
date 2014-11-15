#!/usr/bin/ruby

# used_space=`df -h / | grep -v "Filesystem" | awk '{print $5}'`

#df = %x[df -k].split("\n")[1..-1].map do |line|
#  fs, size, used, free, use, mounted = line.split
#  {fs: fs, use: use}
#end.sort{|a, b| b[:use] <=> a[:use]}

#df = %x[df -k].split("\n")[1..-1].map do |line|
#   line = line.split
#   {fs: line[0], size: line[1], used: line[2], free: line[3], use: line[4].to_i}
#end.sort{|a, b| b[:use] <=> a[:use]}

df = %x[df -k].split("\n")[1..-1].map do |line|
  fs, size, used, free, use, mounted = line.split
  {fs: fs, use: use.to_i}
end.sort{|a, b| b[:use] <=> a[:use]}

str = "Usage of most filled fs #{df[0][:fs]} is #{df[0][:use]}%"

case df[0][:use] <=> 85
when -1
  puts "OK - #{str}"; exit 0
when 0
  puts "WARNING - #{str}"; exit 1
when 1
  puts "CRITICAL - #{str}"; exit 2
else
  puts "UNKNOWN - #{str}"; exit 3
end
