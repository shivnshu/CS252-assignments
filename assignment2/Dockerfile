FROM ubuntu:18.04

ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update && apt-get install -y software-properties-common
RUN add-apt-repository ppa:ondrej/php
RUN apt-get update && apt-get install php5.6 php5.6-mongo -y

EXPOSE 8000

ENTRYPOINT ["php", "-S", "0.0.0.0:8000", "-t", "/web"]

# Usage: docker run -it -p 8000:8000 -v $(pwd):/web <image>
