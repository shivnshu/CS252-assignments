FROM ubuntu:18.04

RUN apt-get update && apt-get install gcc -y

ADD . .
RUN gcc updated_server.c -o server.out
EXPOSE 5433

ENTRYPOINT ["./server.out"]
