package com.ukr.pochta.HttpsServer;


import java.io.FileInputStream;
import java.net.InetSocketAddress;
import java.security.KeyStore;

import javax.net.ssl.KeyManagerFactory;
import javax.net.ssl.SSLContext;
import javax.net.ssl.SSLEngine;
import javax.net.ssl.SSLParameters;
import javax.net.ssl.TrustManagerFactory;

import com.sun.net.httpserver.HttpsConfigurator;
import com.sun.net.httpserver.HttpsParameters;
import com.sun.net.httpserver.HttpsServer;
import com.ukr.pochta.func.Settings;

public class HttpServer implements Runnable {
	

	private static HttpServer serverInstance;
	private static HttpsServer httpsServer;
	
	 @Override
	    public void run() {
		 try {
	   
	            InetSocketAddress address = new InetSocketAddress(Integer.valueOf(Settings.GetSett("ServerPort")));


	            httpsServer = HttpsServer.create(address, 0);
	            SSLContext sslContext = SSLContext.getInstance("TLSv1.2");

	      
	            char[] password = Settings.GetSett("SSLkeypass").toCharArray();
	            KeyStore ks = KeyStore.getInstance("JKS");
	            FileInputStream fis = new FileInputStream(Settings.GetSett("SSLkeystore"));
	            ks.load(fis, password);

	     
	            KeyManagerFactory kmf = KeyManagerFactory.getInstance("SunX509");
	            kmf.init(ks, password);


	            TrustManagerFactory tmf = TrustManagerFactory.getInstance("SunX509");
	            tmf.init(ks);

	       
	            sslContext.init(kmf.getKeyManagers(), tmf.getTrustManagers(), null);
	            httpsServer.setHttpsConfigurator(new HttpsConfigurator(sslContext) {
	                public void configure(HttpsParameters params) {
	                    try {

	                        SSLContext c = SSLContext.getDefault();
	                        SSLEngine engine = c.createSSLEngine();
	                        params.setNeedClientAuth(false);
	                        params.setCipherSuites(engine.getEnabledCipherSuites());
	                        params.setProtocols(engine.getEnabledProtocols());


	                        SSLParameters defaultSSLParameters = c.getDefaultSSLParameters();
	                        params.setSSLParameters(defaultSSLParameters);

	                    } catch (Exception ex) {
	                        System.out.println("Failed to create HTTPS port");
	                    }
	                }
	            });

	            
	            
	            httpsServer.createContext("/", new RootHandler());
	            httpsServer.createContext("/AddTransac", new AddTransac());
	            httpsServer.createContext("/CheckStatus", new CheckStatus());

	            
	            
	            
	            httpsServer.setExecutor(null); 
	            httpsServer.start();
	            
	            
	            
	            System.out.println ("Server start is ok. Listening  " + httpsServer.getAddress() );
	            

	        } catch (Exception exception) {
	            System.out.println("Failed to create HTTPS server on port " + Settings.GetSett("ServerPort") + " of localhost");
	            exception.printStackTrace();

	        }
	    }

	    public static void shutdown() {

	        try { 
	            System.out.println("Shutting down PinBlock Server.");            
	            serverInstance.httpsServer.stop(0);

	        } catch (Exception e) {
	            e.printStackTrace();
	        }

	        try {
	        synchronized (serverInstance) {
	            serverInstance.notifyAll();
	        }
	        }
	        catch (Exception ex) {}


}
}
