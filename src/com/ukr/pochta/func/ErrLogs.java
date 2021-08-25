package com.ukr.pochta.func;

import java.io.FileWriter;
import java.io.IOException;
import java.text.SimpleDateFormat;
import java.util.Date;


public class ErrLogs {
	
	public static void main (String Type, String InpDat, StackTraceElement[] stackTraceElements)
	{
		
		SimpleDateFormat GetDate = new SimpleDateFormat("yyyy-MM-dd");
		SimpleDateFormat GetTime = new SimpleDateFormat("HH:mm:ss");
		Date date = new Date();
		
		String SobrErr="";
		
		if (stackTraceElements!=null)
		{
		for (int i = 0; i<=stackTraceElements.length-1; i++)
			SobrErr+="["+i+"] FileName: " + stackTraceElements [i].getFileName() + ", LineNumber: " + stackTraceElements [i].getLineNumber()+"; ";
		}
		
		FileWriter fw;
		try {
			fw = new FileWriter(Settings.GetSett("PathLogs") +GetDate.format(date)+".logs", true);
			fw.write("["+Type+"]"+ InpDat+" {"+ SobrErr +"} [" + GetTime.format(date) +"]\n");
			fw.close();
		} catch (IOException e) {
			e.printStackTrace();
		}
		
    	
		
	}

}
