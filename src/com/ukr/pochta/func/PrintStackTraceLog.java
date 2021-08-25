package com.ukr.pochta.func;

import java.io.FileWriter;
import java.io.IOException;
import java.io.PrintWriter;
import java.io.StringWriter;
import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.Date;

public class PrintStackTraceLog {

	

	public static void main(String Mess, Exception as, String DataIns) throws IOException {
		
		Globals.is_err = true;
		
		DateFormat DateGet = new SimpleDateFormat("YYYY-MM-dd");
    	Date date = new Date();
    	
    	DateFormat GetTime = new SimpleDateFormat("HH:mm:ss");
    	

		
    	StringWriter errors = new StringWriter();
		as.printStackTrace(new PrintWriter(errors));
		
    	
		FileWriter sw = new FileWriter("ErrLogs/"+DateGet.format(date)+".logs",true);

	       sw.write(GetTime.format(date) + " \n");
	       sw.write(Mess + "\n");
	       sw.write(errors.toString() + "\n");
	       sw.write(DataIns + "\n");
	       sw.write("----------------------------------------------------------------- \n");
	       sw.close();
		
	}

	
	
	
}
