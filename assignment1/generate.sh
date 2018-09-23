html_top="<html>
<head><title>Assignment_1</title></head>
<body>"
html_bot="
</center>
<body>
<html>"
cars=$1
dogs=$2
trucks=$3
cats=$4
str="<center>
"
if [[ $cars -gt 0 ]]
then
str="$str
<h4><u>Cars</u></h4>
<br>
"
fi

for i in `seq 1 $cars`;
do
	var="car$i.jpeg"
	addr=$(base64 $var)
	embedded_data="data:image/png;base64,$addr"	
	str="$str
	<img src=\"$embedded_data\" height=\"256\"  width=\"256 \" >
	"
done

if [[ $dogs -gt 0 ]]
then
str="$str
<h4><u>Dogs</u></h4>
<br>
"
fi

for i in `seq 1 $dogs`;
do
	var="dogs$i.jpeg"

	addr=$(base64 $var)
	embedded_data="data:image/png;base64,$addr"	
	str="$str
	<img src=\"$embedded_data\" height=\"256\"  width=\"256 \" >
	"
done

if [[ $trucks -gt 0 ]]
then
str="$str
<h4><u>Trucks</u></h4>
<br>
"
fi


for i in `seq 1 $trucks`;
do
	var="truck$i.jpeg"

	addr=$(base64 $var)
	embedded_data="data:image/png;base64,$addr"	
	str="$str
	<img src=\"$embedded_data\" height=\"256\"  width=\"256 \" >
	"
done

if [[ $cats -gt 0 ]]
then
str="$str
<h4><u>Cats</u></h4>
<br>
"
fi


for i in `seq 1 $cats`;
do
	var="cat$i.jpeg"

	addr=$(base64 $var)
	embedded_data="data:image/png;base64,$addr"	
	str="$str
	<img src=\"$embedded_data\" height=\"256\"  width=\"256 \" >
	"
done
echo "$html_top$str$html_bot" >option.html
#xdg-open option.html
