<?php
/*** set the content type header ***/
/*** Without this header, it wont work ***/
header("Content-type: text/css");
?>
body {
margin: 0px;
}

ul {
list-style-type: none;
margin: 0;
padding: 0;
overflow: hidden;
background-color: #333;
}

li {
float: left;
border-right: 1px solid #bbb;
}

li a {
display: block;
color: white;
text-align: center;
padding: 14px 16px;
text-decoration: none;
}

li:last-child {
border-right: none;
}

li a:hover {
background-color: #111;
}

.witaj {
display: block;
color: white;
text-align: center;
text-decoration: none;
}

.witaj a:hover {
background-color: #333;
}

.active {
background-color: #4CAF50;
}

table {
border-collapse: collapse;
width: 100%;
}

th, td {
text-align: left;
padding: 8px;
border-bottom: 1px solid #ddd;
}

tr:hover {background-color: #f5f5f5}

th {
background-color: #4CAF50;
color: white;
}

#dodaj input[type=text] {
width: 120px;
padding: 10px 10px;
margin: 8px 10px;
display: inline;
border: 1px solid #ccc;
border-radius: 4px;
box-sizing: border-box;
}

#dodaj select {
width: 210px;
padding: 10px 10px;
margin: 8px 10px;
display: inline;
border: 1px solid #ccc;
border-radius: 4px;
box-sizing: border-box;
}

#dodaj input[type=submit] {
width: 100px;
background-color: #367C39;
color: white;
padding: 12px 20px;
margin: 8px 0;
border: none;
border-radius: 4px;
cursor: pointer;
}

#dodaj input[type=submit]:hover {
background-color: #204922;
}

#dodaj {
background-color: #4CAF50;
padding: 5px 20px;
}

.h_line {
width:100%;
height:1px;
background: #fff;
}

#edycja input[type=submit] {
width: 60px;
background-color: #367C39;
color: white;
padding: 5px 10px;
border: none;
border-radius: 4px;
cursor: pointer;
}

#edycja input[type=submit]:hover {
background-color: #204922;
}

.card {
box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
transition: 0.3s;
border-radius: 5px;
margin: auto;
width: 25%;
transform: translate(0%, 30%);
}

#edytuj input[type=text], #edytuj select {
width: 100%;
padding: 12px 20px;
margin: 8px 0;
display: inline-block;
border: 1px solid #ccc;
border-radius: 4px;
box-sizing: border-box;
}

#edytuj label {
width: 100%;
padding: 2px;
display: inline-block;
}

#edytuj input[type=submit] {
width: 100%;
background-color: #4CAF50;
color: white;
padding: 14px 20px;
margin: 8px 0;
border: none;
border-radius: 4px;
cursor: pointer;
}

#edytuj input[type=submit]:hover {
background-color: #204922;
}

#edytuj {
border-radius: 5px;
background-color: #f2f2f2;
padding: 20px;
}

.alert {
padding: 20px;
background-color: #f44336;
color: white;
opacity: 1;
transition: opacity 0.6s;
margin-bottom: 1px;
}

.alert.success {background-color: #4CAF50;}
.alert.info {background-color: #2196F3;}
.alert.warning {background-color: #ff9800;}

.closebtn {
margin-left: 15px;
color: white;
font-weight: bold;
float: right;
font-size: 22px;
line-height: 20px;
cursor: pointer;
transition: 0.3s;
}

.closebtn:hover {
color: black;
}