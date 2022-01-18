FROM ubuntu:20.04

ENV DEBIAN_FRONTEND=noninteractive 

RUN apt-get update
RUN apt-get install php php-pgsql git -y

WORKDIR /source
RUN git clone https://github.com/WarreKiekens/api.git
