import http from "k6/http";
import { sleep } from 'k6';

export const options = {
    vus: 50,
    duration: '5m',
  };

var i = 0;
export default function() {

    const payload = JSON.stringify({
        url: 'http://localhost:8000/teste-'+i,	
      });
      i++;

    const params = {
        headers: {
          'Content-Type': 'application/json',
        },
    };

    let response = http.post("http://localhost:8000/api/encurtar", payload, params);
    sleep(1);
};