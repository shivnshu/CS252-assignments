#include<stdio.h>
#include<string.h>
#include<stdlib.h>

int main(){
	char ch[100]="4 dogs 4 cats 4 cars 4 trucks";
	int cats=0;int trucks=0;int dogs=0;int cars=0;
	char *split=strtok(ch," ");
	int num;
	while(split!=NULL)
	{	
		//printf("%d\n",split[0]);
		if(strlen(split)==1)
		{
			if(split[0]>=48 && split[0]<=57)
			{
				num=split[0]-48;
				split=strtok(NULL," ");
				if(strcmp(split,"cats")==0)cats+=num;
				else if(strcmp(split,"dogs")==0)dogs+=num;
				else if(strcmp(split,"trucks")==0)trucks+=num;
				else if(strcmp(split,"cars")==0)cars+=num;		
			}
		}
		split=strtok(NULL," ");
	}
	char *buffer=(char*)malloc(sizeof(int));
	char str[]="bash generate.sh ";
	sprintf(buffer,"%d ",cars);
	strcat(str,buffer);
	sprintf(buffer,"%d ",dogs);
	strcat(str,buffer);
	sprintf(buffer,"%d ",trucks);
	strcat(str,buffer);
	sprintf(buffer,"%d ",cats);
	strcat(str,buffer);
	system(str);
}