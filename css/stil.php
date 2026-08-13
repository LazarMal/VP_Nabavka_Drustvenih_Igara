<?php
echo "<style>";

echo "
/* CSS Document */

body{ vertical-align: middle;margin:0px; padding:0px; background:#FFFFFF url(../images/blue-background3.jpg) left top repeat;
}
p{margin:0px; padding:0px;
}
img{border:0px;
}
table {
    border-collapse:collapse;
}

table.no-spacing {
  border-spacing:0; /* Removes the cell spacing via CSS */
  border-collapse: collapse;  /* Optional - if you don't want to have double border where cells touch */
}

.flt{ float:left;
}
.flt1{ clear:left; float:center;
}
.flt2{ float:right;
}
.flt3{ float:left;
}

.topblock{}
.tp_txtplay{ font:bold 20px Trebuchet MS; color:#000000; text-decoration:none;}	
.tp_txtcss{ font:bold 22px Trebuchet MS; color:#000000; }
.tp_home{ font:bold  16px Trebuchet MS; color:#000000; margin:0px; text-decoration:none;}
.tp_home2{ font:bold 16px Trebuchet MS; color:#000000; margin:0px; text-decoration:none;}
.tp_pipe{display:inline; margin:0px 10px 0px 10px;}	
#cpblock{float:left; display:inline; margin:0px 0px 0px 50px; width:898px;}
.cpinner{display:inline; margin:0px; width:882px; border-left:8px solid #000000; border-right:8px solid #000000; background:#448CCB url(../images/cp_bg.gif) repeat-x;}
.lp_block{ display:inline; width:260px; margin:0px 20px 0px 21px;}
.lp_imgpair{ margin:50px 0px 0px 10px;}
.lp_txtmem,.lp_txtour,.lp_txtclint{ font:bold 12px/14px Trebuchet MS; color:#000000; margin:80px 0px 0px 10px;}
.lp_txtour{ color:#36382A; margin:10px 0px 0px 24px;}
.lp_txtclint{ margin:5px 0px 0px 9px;}
.lp_txtlog,.lp_txtlog2{ font:12px/14px Trebuchet MS; color:#000000; margin:18px 0px 0px 18px; width:54px;}
.lp_txtlog2{ margin:6px 0px 0px 18px;}
.lp_textbox,.lp_textbox2{ display:inline; width:110px; height:16px; margin:14px 0px 0px 8px; border:1px solid #8AC5E5;}
.lp_textbox2{ margin:4px 0px 0px 8px; border:1px solid #8AC5E5;}
.lp_arrow{ margin:4px 0px 0px 8px;}
.lp_boxtop1{ margin:28px 1px 0px 1px;}
.lp_boxtop2{ margin:0px 1px 0px 1px;}
.lp_boxbg{width:258px; display:inline; background:#E4F0CD; margin:0px 1px 0px 1px; padding-bottom:24px;}
.lp_boxtxt,.lp_boxtxt2,.lp_box2txt{ font:12px/14px Trebuchet MS; color:#5F5F5F; margin:12px 0px 0px 24px; width:196px;}
.lp_box2txt{ font:12px/14px Trebuchet MS; color:#000000; margin:7px 0px 0px 27px; width:210px;}
.lp_boxtxt2{ font:12px/14px Trebuchet MS; color:#36382A; margin:7px 0px 0px 10px; width:136px;}
.lp_boxbult{ margin:13px 1px 0px 34px;}
.lp_boxtop3{ margin:16px 0px 0px 0px;}
.lp_box2bg{ display:inline; width:260px; background:url(../images/lp_box2bg.gif) repeat-y; padding-bottom:6px;}
.lp_imgclient{ margin:3px 0px 0px 27px;}
.rp_block{ display:inline; width:560px;}
.rp_inner{ display:inline; width:570px; margin:0px; background:#D8E7F4 url(../images/rp_bg.gif) repeat-x; padding-bottom:10px;}
.rp_txtour{ font: bold italic 20px Trebuchet MS; color:#99204E; margin:0px 0px 0px 0px;}
.rp_abacus{ margin:30px 0px 0px 40px;}
.rp_weltxt{ font:12px/14px Trebuchet MS; color:#5F5F5F; margin:26px 0px 0px 26px; width:378px;}
.rp_linktxt{ font:12px/14px Trebuchet MS; color:#BD2E12; margin:8px 0px 0px 10px; width:350px;}
.rp_read{ width:91px; height:16px; background:url(../images/cp_butt.gif) no-repeat; margin:10px 0px 0px 0px;}
.rp_line{ margin:20px 0px 0px 24px;}
.rp_bult{ margin:13px 0px 0px 0px;}
.rp_topcornn{margin:15px 0px 0px 0px;}
.rp_nameband{margin:30px 0px 0px 10px;}
.rp_weltxt2{ font:12px/14px Trebuchet MS; color:#5F5F5F; margin:14px 0px 0px 0px; width:378px;}
.ft_bg{width:1000px; height:50px; background:#000000; margin:0px;}
.ft_txt,.ft_txt2,.ft_txt3{ font:bold italic 13px/15px Trebuchet MS; color:#000000; margin:16px 0px 0px 206px; text-decoration:none;}
.ft_txt2{margin:16px 0px 0px 0px;}
.ft_txt3{font:12px/14px Trebuchet MS; color:#555555; margin:0px 0px 0px 220px; width:1050px;}
.ft_txt4{font:12px/14px Trebuchet MS; color:#555555; margin:50px 0px 0px 220px; width:1050px;}
.ft_bult{margin:24px 20px 0px 20px;}
.ft_button{margin:10px 0px 0px 15px;}
.ft_button2{margin:10px 0px 0px 6px;}
.ft_email{color:#CCBBAA;text-decoration:underline;}
a:link {
    color: darkblue;
    text-decoration:none;
    background-color: #B7F3FE;
}

/* visited link */
a:visited {
    color:  darkblue;
    text-decoration:none;
    background-color: #B7F3FE;
}

/* mouse over link */
a:hover {
    color: black;
    text-decoration:underline;
    background-color: #B7F3FE;
}

/* selected link */
a:active {
    color: magenta;
    background-color: #B7F3FE;
}

@media print {
    .no-print {
        display: none !important;
    }
} ";

echo "</style>";

?>