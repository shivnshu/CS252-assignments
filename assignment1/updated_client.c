#include <stdio.h>
#include <stdlib.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <string.h>
#include <arpa/inet.h>
#include <unistd.h>
#define MAX 1700000
#define MAX_REQ 100
#define LENGTH 512

#define SHELLSCRIPT "\
#/bin/bash \n\
xdg-open response.html\
"

int main(int argc, char *argv[]){
  if(argc<3){
      printf("pass the IP and portno of server\n");
      return 0;
  }
  int clientSocket;
  char revbuf[LENGTH+1], request[MAX_REQ];
  struct sockaddr_in serverAddr;
  socklen_t addr_size;

  int PORT = atoi(argv[2]);

  clientSocket = socket(PF_INET, SOCK_STREAM, 0);
 
  serverAddr.sin_family = AF_INET;

  serverAddr.sin_port = htons(PORT);
  /* Set IP address to localhost */
  serverAddr.sin_addr.s_addr = inet_addr(argv[1]);

  memset(serverAddr.sin_zero, '\0', sizeof serverAddr.sin_zero);

  addr_size = sizeof serverAddr;
  connect(clientSocket, (struct sockaddr *) &serverAddr, addr_size);

  printf("Maximum request allowed---> 4 each\n");
  printf("Enter your request eg - 3 cats 4 trucks and 1 dog :   ");
  fgets(request, MAX, stdin);
  send(clientSocket, request, sizeof(request), 0);
  printf("Request sent successfully\n");
  
  FILE *fp = fopen("response.html","w");
       bzero(revbuf, LENGTH);
        int f_block_sz = 0;
        int success = 0;
        while(success == 0)
        {
            while(f_block_sz = recv(clientSocket, revbuf, LENGTH, 0))
            {
                if(f_block_sz < 0)
                {
                    printf("Receive file error.\n");
                    break;
                }
                int write_sz = fwrite(revbuf, sizeof(char), f_block_sz, fp);
                if(write_sz < f_block_sz)
                {
                    printf("File write failed.\n");
                    break;
                }
                bzero(revbuf, LENGTH);
            }
          success=1;
          fclose(fp);
           
        }
   
  system(SHELLSCRIPT);






















  
  return 0;
}
