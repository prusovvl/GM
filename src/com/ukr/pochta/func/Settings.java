package com.ukr.pochta.func;

import java.io.BufferedReader;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStreamReader;
import java.util.HashMap;

public class Settings {
	
	private static HashMap <String, String> Settings = new HashMap <String, String> (); 
	
	public static void setSett (String SettFile) throws IOException
	{
		BufferedReader br = new BufferedReader(
				   new InputStreamReader(
		                      new FileInputStream(SettFile)));
			
			
	try {
	   StringBuilder sb = new StringBuilder();
	   String line = br.readLine();

	   while (line != null) {


		String ClearLine = line.split("#")[0].trim();
		
		
		   if (!ClearLine.trim().equals(""))
			   Settings.put(ClearLine.split("=")[0].trim(), ClearLine.split("=")[1].trim());
	    
		   line = br.readLine();
	    
	   }
	   
	} finally {
	   br.close();
	}
	}
	
	public static String GetSett (String Key)
	{
		return Settings.get(Key);
	}

}
