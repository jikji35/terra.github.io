// JavaScript Document


// 밑으로 떨어지는 크로바이미지!! PhotoScape X프로그램에서 이미지레이어로 깔끔하게편집(사인,도장등등)
/*
grphcs=new Array(6)
Image0=new Image();
Image0.src=grphcs[0]="image/c1.png";
Image1=new Image();
Image1.src=grphcs[1]="image/c2.png"
Image2=new Image();
Image2.src=grphcs[2]="image/c3.png"
Image3=new Image();
Image3.src=grphcs[3]="image/c4.png"
Image4=new Image();
Image4.src=grphcs[4]="image/c5.png"
Image5=new Image();
Image5.src=grphcs[5]="image/c6.png" 

Amount=8; // 이미지의 갯수
Ypos=new Array();
Xpos=new Array();
Speed=new Array();
Step=new Array();
Cstep=new Array();
ns=(document.layers)?1:0;
ns6=(document.getElementById&&!document.all)?1:0;

if (ns){
for (i = 0; i < Amount; i++){
var P=Math.floor(Math.random()*grphcs.length);
rndPic=grphcs[P];
document.write("<LAYER NAME='sn"+i+"' LEFT=0 TOP=0><img src="+rndPic+"></LAYER>");
}
}
else{
document.write('<div style="position:absolute;top:0px;left:0px"><div style="position:relative">');
for (i = 0; i < Amount; i++){
var P=Math.floor(Math.random()*grphcs.length);
rndPic=grphcs[P];
document.write('<img id="si'+i+'" src="'+rndPic+'" style="position:absolute;top:0px;left:0px">');
}
document.write('</div></div>');
}
WinHeight=(ns||ns6)?window.innerHeight:window.document.body.clientHeight;
WinWidth=(ns||ns6)?window.innerWidth-70:window.document.body.clientWidth;
for (i=0; i < Amount; i++){                                                                
 Ypos[i] = Math.round(Math.random()*WinHeight);
 Xpos[i] = Math.round(Math.random()*WinWidth);
 Speed[i]= Math.random()*1+1;
 Cstep[i]=0;
 Step[i]=Math.random()*0.1+0.05;
}
function fall(){
var WinHeight=(ns||ns6)?window.innerHeight:window.document.body.clientHeight;
var WinWidth=(ns||ns6)?window.innerWidth-70:window.document.body.clientWidth;
var hscrll=(ns||ns6)?window.pageYOffset:document.body.scrollTop;
var wscrll=(ns||ns6)?window.pageXOffset:document.body.scrollLeft;
for (i=0; i < Amount; i++){
sy = Speed[i]*Math.sin(90*Math.PI/180);
sx = Speed[i]*Math.cos(Cstep[i]);
Ypos[i]+=sy;
Xpos[i]+=sx; 
if (Ypos[i] > WinHeight){
Ypos[i]=-60;
Xpos[i]=Math.round(Math.random()*WinWidth);
Speed[i]=Math.random()*1+1;
}
if (ns){
document.layers['sn'+i].left=Xpos[i];
document.layers['sn'+i].top=Ypos[i]+hscrll;
}
else if (ns6){
document.getElementById("si"+i).style.left=Math.min(WinWidth,Xpos[i]);
document.getElementById("si"+i).style.top=Ypos[i]+hscrll;
}
else{
eval("document.all.si"+i).style.left=Xpos[i];
eval("document.all.si"+i).style.top=Ypos[i]+hscrll;
} 
Cstep[i]+=Step[i];
}
setTimeout('fall()',20);
}

window.onload=fall
*/


               
function Clock() { 
            var date = new Date(); 
            var YYYY = String(date.getFullYear()); 
            var MM = String(date.getMonth() + 1); 
            var DD = Zero(date.getDate()); 
            var hh = Zero(date.getHours()); 
            var mm = Zero(date.getMinutes());
            var ss = Zero(date.getSeconds()); 
            var Week = Weekday(); 
            Write(YYYY, MM, DD, hh, mm, ss, Week); 
            //시계에 1의자리수가 나올때 0을 넣어주는 함수 (ex : 1초 -> 01초) 
            function Zero(num) { 
                return (num < 10 ? '0' + num : '' + num); } 
            //요일을 추가해주는 함수 
            function Weekday() { 
                var Week = ['일', '월', '화', '수', '목', '금', '토']; 
                var Weekday = date.getDay(); 
                return Week[Weekday]; 
            } 
            //시계부분을 써주는 함수 
            function Write(YYYY, MM, DD, hh, mm, ss, Week) { 
                var Clockday = document.getElementById("Clockday"); 
                var Clock = document.getElementById("Clock"); 
                Clockday.innerText = YYYY + '/' + MM + '/' + DD + '(' + Week + ')'; Clock.innerText = hh + ':' + mm + ':' + ss; } 
        } setInterval(Clock, 1000); 
        //1초(1000)마다 Clock함수를 재실행 한다 
     
     
           


// 큐브 회전


  
        let deg = 0;
        setInterval(()=>{
            deg = deg - 90;
            document.querySelector(".cube").style.transform
            = 'rotateX(' + deg + 'deg)';
        }, 1000)
                             
                        
             
