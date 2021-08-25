package com.ukr.pochta.HttpsServer;

import java.io.IOException;
import java.io.OutputStream;



import com.sun.net.httpserver.HttpExchange;
import com.sun.net.httpserver.HttpHandler;

public class RootHandler implements HttpHandler {

	@Override
	public void handle(HttpExchange he) throws IOException {
		// TODO Auto-generated method stub
		Thread RootTh = new Thread(new Runnable() {
		    public void run() {
		
		 String response = "Server UrkPochta is run";
                 try {
					he.sendResponseHeaders(200, response.length());
					 OutputStream os = he.getResponseBody();
	                 os.write(response.getBytes());
	                 os.close();
				} catch (IOException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
                
		    }      
		    });
		RootTh.start();

	}

}
