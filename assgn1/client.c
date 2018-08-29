#include <stdio.h>
#include <stdlib.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <string.h>
#include <arpa/inet.h>
/* Check availability for PORT */
#define PORT 5433
#define MAX 4096

#define SHELLSCRIPT "\
#/bin/bash \n\
google-chrome response.html\
"

int main(){
  int clientSocket;
  char buffer[MAX], request[MAX];
  struct sockaddr_in serverAddr;
  socklen_t addr_size;

  /*---- Create the socket. The three arguments are: ----*/
  /* 1) Internet domain 2) Stream socket 3) Default protocol (TCP in this case) */
  clientSocket = socket(PF_INET, SOCK_STREAM, 0);

  /*---- Configure settings of the server address struct ----*/
  /* Address family = Internet */
  serverAddr.sin_family = AF_INET;
  /* Set port number, using htons function to use proper byte order */
  serverAddr.sin_port = htons(PORT);
  /* Set IP address to localhost */
  serverAddr.sin_addr.s_addr = inet_addr("127.0.0.1");
  /* Set all bits of the padding field to 0 */
  memset(serverAddr.sin_zero, '\0', sizeof serverAddr.sin_zero);

  /*---- Connect the socket to the server using the address struct ----*/
  addr_size = sizeof serverAddr;
  connect(clientSocket, (struct sockaddr *) &serverAddr, addr_size);

  /*---- Send the request to the server ----*/
  printf("Enter your request eg - 3 cat :   ");
  fgets(request, MAX, stdin);
  send(clientSocket, request, sizeof(request), 0);
  printf("Request sent successfully\n");
  
  /*---- Save server response in buffer and then paste it in .html file ----*/
  recv(clientSocket, buffer, MAX, 0);
  FILE *fptr = fopen("response.html","w");
  fprintf(fptr,"%s", buffer);
  fclose(fptr);
  //system(SHELLSCRIPT);
  
  return 0;
}
