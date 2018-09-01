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
#define LENGTH 512

int main(){
    int welcomeSocket, newSocket, n;
    struct sockaddr_in serverAddr;
    struct sockaddr_storage serverStorage;
    socklen_t addr_size;
    char req[MAX];


    welcomeSocket = socket(PF_INET, SOCK_STREAM, 0);

    serverAddr.sin_family = AF_INET;

    serverAddr.sin_port = htons(PORT);
    
    serverAddr.sin_addr.s_addr = inet_addr("0.0.0.0");
    
    memset(serverAddr.sin_zero, '\0', sizeof serverAddr.sin_zero);

    n = bind(welcomeSocket, (struct sockaddr *) &serverAddr, sizeof(serverAddr));
    if(n<0){ 
        printf("ERROR on bind\n");
        return 0;
    }


while(1){
    if(listen(welcomeSocket,5)==0)
        printf("Server_bashed initialised\n");
    else
        printf("Error\n");

    addr_size = sizeof serverStorage;
    


    newSocket = accept(welcomeSocket, (struct sockaddr *) &serverStorage, &addr_size);



    recv(newSocket, req, MAX, 0);
    printf("Request received successfully\n");
   
    char *token = strtok(req, " "), image_name[5];
    int quantity=0;
    int cats=0, cars=0, dogs=0, trucks=0;
    while(token != NULL){
        if(strlen(token)==1 && token[0]>='1' && token[0]<='4') quantity=token[0]-'0';
        else if(strcmp(token,"and")!=0){
            if(strncmp(token,"dog",3)==0) 
                dogs += quantity;
            if(strncmp(token,"cat",3)==0) 
                cats+=quantity;
            if(strncmp(token,"car",3)==0) 
                cars+=quantity;
            if(strncmp(token,"tru",3)==0)
                trucks+=quantity;
      
        }        
        token = strtok(NULL, " ");
    }
  
    char *buffer=(char*)malloc(sizeof(int));
    char str[]="bash generate.sh ";
    char *sdbuf=NULL;
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
    
    FILE *fp = fopen("option.html","r");
    fseek(fp, 0, SEEK_END);
    long fsize = ftell(fp);
    fseek(fp, 0, SEEK_SET);
    sdbuf = malloc(fsize + 1);
    bzero(sdbuf, LENGTH);
            int f_block_sz;
            while((f_block_sz = fread(sdbuf, sizeof(char), LENGTH, fp))>0)
            {
                if(send(newSocket, sdbuf, f_block_sz, 0) < 0)
                {
                    printf("File sent failed");
                    break;
                }
                bzero(sdbuf, LENGTH);
            }

    printf("Data sent to client\n");
   close(newSocket);
    system("clear");

   

}

    close(welcomeSocket);
    return 0;
}
