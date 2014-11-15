#!/usr/bin/env ruby

require 'rubygems'
require 'bundler/setup'
require 'nokogiri'
require 'open-uri'
require 'net/http'
require 'slop'

class Ip2sms
  attr_accessor :phone, :text

  def opts
    @opts ||= Slop.parse do
      banner "Usage: #{$0} [options]"

      on 'u', :url=, 'URL'
      on 'l', :login=, 'Login'
      on 'p', :password=, 'Password'
      on 'n', :phone=, 'Phone number'
      on 'm', :message=, 'Message'
      on 's', :source=, 'Source'
    end

    unless @opts[:login] && @opts[:password] && @opts[:message]
      puts @opts
      exit
    end
    @opts
  end

  def initialize
    message = if opts[:message] == '-'
                STDIN.read
              else
                opts[:message]
              end
    @body = xml opts[:phone], message
  end

  def uri
    URI.parse opts[:url]
  end

  def request
    req = Net::HTTP::Post.new uri.path
    req.content_type = "text/xml"
    req.body = @body
    req.basic_auth opts[:login], opts[:password]
    req
  end

  def send
    http = Net::HTTP.new(uri.host, uri.port)
    http.use_ssl = true
    response = http.request request
    response.body
  end

  def xml phone, text
    Nokogiri::XML::Builder.new do |xml|
      xml.message do
        xml.service source: opts[:source], id: 'single'
        xml.to phone
        xml.body("content-type" => 'text/plain'){ xml.text text }
      end
    end.doc.root.to_xml
  end
end

if __FILE__ == $0
  Ip2sms.new.send
end
