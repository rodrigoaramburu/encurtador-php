import http from "k6/http";
import { sleep } from 'k6';

export const options = {
  vus: 50,
  duration: '5m',
};

const ids = [
"d39lDe", "lQ5Rm7", "2p0l8M", "woqMOT", "rpnszN", "GpR0wq", "cFLwO7", "eTVeJc", "mCOLlf", "ctUzvf", "gSOvi1",
"us2Fk2", "2OESeM", "AedJg5", "SInvYJ", "eBOqXQ", "GLwKjg", "w4jyYW", "zdGjkA", "2i2j5J", "x3Wr7X", "oH6Ay5",
"1a0B1t", "XmqhZ0", "ljdGTz", "1xKvCJ", "dvQsqz", "L5F9La", "kNoxg7", "rio83N", "Z8AOn0", "x0e6Cm", "fSAcLg", 
"HrBiJ8", "xa2Rjb", "ooIP7Q", "W6ww5N", "f8Uq1a", "gfhpfi", "cRNtSb", "fBrocF", "bNGmSf", "0C1H84", "kjE7Tb",
"n7dHkU", "5p479w", "W1QdIP", "bU7pGJ", "ikCJJ5", "9FrNYO", "cNhNw6", "mwiTmX", "HTspUM", "vd4wcx", "mj9xop",
"dO1NWN", "ACRo9g", "Hay6Ir", "HVo2hB", "E0ReEH", "nCqs1F", "kqAo9l", "yjsUzV", "lG7OfE", "u37JNf", "HCM4UR", 
"OyU6F4", "4ZGSGQ", "ilKfwv", "prNvu1", "8ZOjOo", "f4TDLu", "kvcWLG", "aniSoq", "xoQeny", "XkWMBF", "8PUmzF", 
"yJiViG", "ZLvQP5", "MmCkvH", "eejME3", "UqMsPX", "hoDAZM", "G9iG5s", "5dmnsi", "R69P3G", "m4IkHv", "1b7sCS", 
"r3NdA7", "uz3ovb", "qXEEkI", "Zhp1us", "yMTUzJ", "8e4T4M", "oEzBFq", "GevuXF", "MaJRnU", "1FXLcJ", "yY8rnO",
"DmfXrB", "qcnohj", "99eXQP", "wa6OOo", "drkYTo", "RdK2k7", "glOeyA", "cq14Qx", "NK8hbT", "yih3Gj", "uVTwr3", 
"2Y9Hqe", "6Q8OPp", "CYIMFJ", "XgNdkZ", "WoIU4h", "mSy5Ha", "lh5QhL", "qmMfID", "S86OFh", "yAGVSR", "2n6ICD",
"QjbDUB", "lpFEYG", "1bbHGr", "crBmV5", "FLT1Wj", "tsVJTs", "wW6v13", "rGz3gX", "OZLGda", "AkpbuQ", "qgbCDJ", 
"UDodAQ", "yp97Ol", "ImSrNQ", "Z9QsAX", "DOHlkg", "VEh0bD", "3fAz5f", "QAmWR1", "Y5o6Yc", "21wcaE", "tAPRle",
"8YEq19", "GZZL3U", "NsCWEy", "LQEwim", "EhdiDS", "Ny9eKg", "fwCLDH", "VyZHQ2", "8oeiJb", "904w9p", "TbNZyP",
"yZH8Ic", "dw6bGq", "Vn2QRG", "uW23IW", "11DxSm", "BqOgcU", "tyaI1z", "EzeNN4", "tPAyPH", "ydFS6G", "DMDEr1",
"1vLpys", "SUyAGD", "ZYIO1E", "gHc6FG", "tAWe5z", "le0Mx9", "rmd9Pl", "Se62re", "sKwPwD", "c1BN2n", "tXZp8S",
"d1LAPm", "wWVluG", "Wzuqsy", "maeUlC", "MS52be", "1zIG3q", "pp8qd4", "zErsXf", "04ZMt3", "ScDyPI", "BW5XB1",
"ra2mNg", "UWFXhZ", "nDMqIJ", "riQnF1", "2G9BeM", "rDIBuL", "eq44gv", "hkufb0", "RxQzMx", "LMezJp", "Ro0gAw",
"TzczUv", "sZ3Oz3"];

var i = 0;
export default function() {
  var id = ids[Math.floor(Math.random() * ids.length)];
  let response = http.get("http://localhost:8000/"+id, { redirects: 0});
  
  sleep(1);
};