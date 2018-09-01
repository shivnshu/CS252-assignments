#include <stdio.h>
#include <stdlib.h>
#include <netinet/in.h>
#include <string.h>
#include <sys/socket.h>
#include <arpa/inet.h>
#include <unistd.h>

/*Check Availability for PORT*/
#define PORT 5433
#define MAX 170000

int main(){
    int welcomeSocket, newSocket, n;
    struct sockaddr_in serverAddr;
    struct sockaddr_storage serverStorage;
    socklen_t addr_size;
    char req[MAX];

    /*---- Create the socket. The three arguments are: ----*/
    /* 1) Internet domain 2) Stream socket 3) Default protocol (TCP in this case) */
    welcomeSocket = socket(PF_INET, SOCK_STREAM, 0);

    /*---- Configure settings of the server address struct ----*/
    /* Address family = Internet */
    serverAddr.sin_family = AF_INET;
    /* Set port number, using htons function to use proper byte order */
    serverAddr.sin_port = htons(PORT);
    /* Set IP address to localhost */
    serverAddr.sin_addr.s_addr = inet_addr("0.0.0.0");
    /* Set all bits of the padding field to 0 */
    memset(serverAddr.sin_zero, '\0', sizeof serverAddr.sin_zero);

    /*---- Bind the address struct to the socket ----*/
    n = bind(welcomeSocket, (struct sockaddr *) &serverAddr, sizeof(serverAddr));
    if(n<0){ 
        printf("ERROR on bind\n");
        return 0;
    }
    /*---- Listen on the socket, with 5 max connection requests queued ----*/
    if(listen(welcomeSocket,5)==0)
        printf("I'm listening\n");
    else
        printf("Error\n");

    /*---- Accept call creates a new socket for the incoming connection ----*/
    addr_size = sizeof serverStorage;
    newSocket = accept(welcomeSocket, (struct sockaddr *) &serverStorage, &addr_size);

    /*---- Receive the request and accordingly generate .html file ----*/
    recv(newSocket, req, MAX, 0);
    printf("Request received successfully\n");
    //char opening[MAX] = "<!DOCTYPE HTML>\n<html>\n<head><title>CS252</title></head>\n<body>\n<table>\n", temp[2]="0";
    //char *closing = "</table>\n</body>\n</html>";
    char *token = strtok(req, " "), image_name[5];
    int quantity=0;
    int cats=0, cars=0, dogs=0, trucks=0;
    while(token != NULL){
        if(strlen(token)==1 && token[0]>='1' && token[0]<='4') quantity=token[0]-'0';
        else if(strcmp(token,"and")!=0){
            if(strncmp(token,"dog",3)==0) //strcpy(image_name,"dog");
                dogs += quantity;
            if(strncmp(token,"cat",3)==0) //strcpy(image_name,"cat");
                cats+=quantity;
            if(strncmp(token,"car",3)==0) //strcpy(image_name,"car");
                cars+=quantity;
            if(strncmp(token,"tru",3)==0) //strcpy(image_name,"truck");
                trucks+=quantity;
            /*for(int i=1;i<=quantity;i++){
                strcat(opening,"<tr>\n<td>");
                temp[0]=i+'0';
                strcat(opening,temp);
                strcat(opening,"</td>\n<td><img src=\"images/");
                strcat(opening,image_name);
                strcat(opening,temp);
                strcat(opening,".jpeg");
                strcat(opening,"\" height=\"60\" width=\"60\"></td>\n</tr>\n");
            }*/
        }        
        token = strtok(NULL, " ");
    }
    //strcat(opening,closing);
    char *buffer=(char*)malloc(sizeof(int));
    char str[]="bash generate.sh ";
    char *fileData=NULL;
    sprintf(buffer,"%d ",cars);
    strcat(str,buffer);
    sprintf(buffer,"%d ",dogs);
    strcat(str,buffer);
    sprintf(buffer,"%d ",trucks);
    strcat(str,buffer);
    sprintf(buffer,"%d ",cats);
    strcat(str,buffer);
    system(str);
    printf("file generated successfully\n");
    /*FILE *fp = fopen("option.html", "ab+");
    fread(fileData, 1, MAX, fp);*/
    FILE *fptr = fopen("option.html","r");
    fseek(fptr, 0, SEEK_END);
    long fsize = ftell(fptr);
    fseek(fptr, 0, SEEK_SET);
    fileData = malloc(fsize + 1);
    fread(fileData, fsize, 1, fptr);
    fclose(fptr);	
    fileData[fsize] = 0;
    send(newSocket, fileData, fsize, 0);  
    printf("Data sent to client\n");
    close(welcomeSocket);
    return 0;
}
