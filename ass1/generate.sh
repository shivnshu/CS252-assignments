html_top="<html>
<head><title>Assignment_1</title></head>
<body>"
html_bot="
<body>
<html>"
cars=$1
dogs=$2
trucks=$3
cats=$4
str=""
for i in `seq 1 $cars`;
do
	var="car$i.jpeg"
	str="$str
	<img src=\"$var\" height=\"256\"  width=\"256 \" >
	"
done

for i in `seq 1 $dogs`;
do
	var="dogs$i.jpeg"
	str="$str
	<img src=\"$var\" height=\"256\"  width=\"256 \" >
	"
done

for i in `seq 1 $trucks`;
do
	var="truck$i.jpeg"
	str="$str
	<img src=\"$var\" height=\"256\"  width=\"256 \" >
	"
done

for i in `seq 1 $cats`;
do
	var="cat$i.jpeg"
	str="$str
	<img src=\"$var\" height=\"256\"  width=\"256 \" >
	"
done
echo "$html_top$str$html_bot" >option.html
xdg-open option.html