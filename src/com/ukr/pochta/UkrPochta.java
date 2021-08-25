package com.ukr.pochta;

import java.io.IOException;

import org.json.simple.JSONObject;

import com.ukr.pochta.HttpsServer.HttpServer;
import com.ukr.pochta.func.Globals;
import com.ukr.pochta.func.MakeDebug;
import com.ukr.pochta.func.MakePost;
import com.ukr.pochta.func.Settings;

public class UkrPochta {
	
	public static void main (String [] args) throws IOException
	{
		if (args.length==0)
		{
			System.out.println("No setting file found. Exit");
			System.exit(0);
		}
		
		Settings.setSett(args[0]);
		

		


		Globals.DEBUG = false;
		
		if (args.length==2)
		{
			if (args[1].equals("debug"))
				Globals.DEBUG = true;
		}
				
		if (Globals.DEBUG)
			MakeDebug.Add("Debag mode enabled");
		
		new TaskMake().run();
		
		
		new HttpServer().run();
		
	}

}
