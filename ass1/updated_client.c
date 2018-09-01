#include <stdio.h>
#include <stdlib.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <string.h>
#include <arpa/inet.h>
//#include <unistd.h>
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
  //struct hostent *server;
  //server = gethostbyname(argv[1]);
  int PORT = atoi(argv[2]);
  /*---- Create the socket. The three arguments are: ----*/
  /* 1) Internet domain 2) Stream socket 3) Default protocol (TCP in this case) */
  clientSocket = socket(PF_INET, SOCK_STREAM, 0);
  /*---- Configure settings of the server address struct ----*/
  /* Address family = Internet */
  serverAddr.sin_family = AF_INET;
  /* Set port number, using htons function to use proper byte order */
  serverAddr.sin_port = htons(PORT);
  /* Set IP address to localhost */
  serverAddr.sin_addr.s_addr = inet_addr(argv[1]);
  //memcpy((char *)server->h_addr, (char *)&serv_addr.sin_addr.s_addr, server->h_length);
  /* Set all bits of the padding field to 0 */
  memset(serverAddr.sin_zero, '\0', sizeof serverAddr.sin_zero);

  /*---- Connect the socket to the server using the address struct ----*/
  addr_size = sizeof serverAddr;
  connect(clientSocket, (struct sockaddr *) &serverAddr, addr_size);

  /*---- Send the request to the server ----*/
  printf("Enter your request eg - 3 cats 4 trucks and 1 dog :   ");
  fgets(request, MAX, stdin);
  send(clientSocket, request, sizeof(request), 0);
  printf("Request sent successfully\n");
  
  /*---- Save server response in buffer and then paste it in .html file ----*/
  //recv(clientSocket, buffer, MAX, 0);
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
