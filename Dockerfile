FROM ubuntu:20.04

ENV DEBIAN_FRONTEND=noninteractive 

RUN apt-get update
RUN apt-get install php php-pgsql git links lsof nano -y

WORKDIR /source
RUN git clone https://github.com/WarreKiekens/api.git

WORKDIR api/
RUN cp docker-sources/api.conf /etc/apache2/sites-available/
RUN a2enmod rewrite
RUN a2ensite api.conf
RUN service apache2 restart

RUN chmod +x start.sh
CMD ./start.sh
