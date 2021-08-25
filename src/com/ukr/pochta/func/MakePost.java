package com.ukr.pochta.func;

import java.io.BufferedReader;
import java.io.DataOutputStream;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.net.HttpURLConnection;
import java.net.URL;
import java.nio.charset.StandardCharsets;
import java.util.List;
import java.util.Map;
import java.util.Set;

public class MakePost {
	
	public static String main (String url, String urlParameters) throws IOException
	{
		String USER_AGENT = "Mozilla/5.0";
	      
	      
			URL obj = new URL(url);
			HttpURLConnection con = (HttpURLConnection) obj.openConnection();
	 
			
			
			con.setConnectTimeout(10000);
			con.setReadTimeout(10000);
			

			con.setRequestMethod("POST");

		//	con.setRequestProperty("Host", "10.255.102.155:8080");

			con.setRequestProperty("Content-Type", "application/json");
			con.setRequestProperty("Accept", "*/*");
			con.setRequestProperty("Accept", "*/*");
			
			con.setDoOutput(true);
			
			if (Globals.DEBUG)
			{
				MakeDebug.Add("Send url: " + url);
				MakeDebug.Add("Send headers: ");
				
				 Map<String, List<String>> hdrs = con.getRequestProperties();
				    Set<String> hdrKeys = hdrs.keySet();
				    
				    for (String k : hdrKeys)
				    	MakeDebug.Add(k + ":" + hdrs.get(k));
				
				MakeDebug.Add("Send body: " + urlParameters);
			}
			
			try {
				
				byte[] out = urlParameters.getBytes(StandardCharsets.UTF_8);

				OutputStream stream = con.getOutputStream();
				stream.write(out);
				stream.close();
				
			}
			catch (Exception e)
			{
				PrintStackTraceLog.main("Send Req", e, "[url:"+url+"][data:"+urlParameters+"]");
				
				if (Globals.DEBUG)
					MakeDebug.Add("Error connect. [url:"+url+"][data:"+urlParameters+"]");
				
				return "error";

			}
	 
			
			
			int responseCode = 0;
			
			try {
			responseCode = con.getResponseCode();
			}
			catch (Exception e)
			{
				
				PrintStackTraceLog.main("Send Req", e, "[url:"+url+"][data:"+urlParameters+"]");
				
				if (Globals.DEBUG)
					MakeDebug.Add("Error connect. [url:"+url+"][data:"+urlParameters+"]");
				
				return "error";
				
			}
			
			
			if (Globals.DEBUG)
				MakeDebug.Add("responseCode: " + responseCode);
			
			if (responseCode!=200)
			{
				
				new Logs("MakeReq", "Get server error. [HttpCode: "+responseCode+"]" + "[url:"+url+"][data:"+urlParameters+"]");
				
				return "error";
			}
			
	 
			BufferedReader in = new BufferedReader(
			        new InputStreamReader(con.getInputStream()));
			String inputLine;
			StringBuffer response = new StringBuffer();
	 
			while ((inputLine = in.readLine()) != null) {
				response.append(inputLine);
			}
			in.close();
		
			return response.toString();
	
	}

}
