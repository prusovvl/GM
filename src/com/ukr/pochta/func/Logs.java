package com.ukr.pochta.func;

import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.FileWriter;
import java.io.IOException;
import java.io.PrintWriter;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.GregorianCalendar;

public class Logs {
	
	
	public Logs(String FileType, String Data) throws FileNotFoundException, IOException {
		
		Calendar cal = new GregorianCalendar();
		
		Data = "[" + new SimpleDateFormat("HH:mm:ss").format(cal.getTime()) + "]"+Data;
		
		
		FileReader fr = null;
		BufferedReader br = null;
		String str = null;
		try {
			fr = new FileReader(Settings.GetSett("PathLogs") + FileType+"_" + new SimpleDateFormat("dd.MM.yyyy").format(cal.getTime())+".logs");	
		br = new BufferedReader(fr);
				
			
		str = br.readLine();

		while (str != null) {
			Data += System.getProperty("line.separator") + str;
		str = br.readLine();

		}
		    }
		catch (FileNotFoundException e1) {
			
			
		}
		
		if (str!=null)
		{
		FileWriter fw = new FileWriter(Settings.GetSett("PathLogs") + FileType+"_" + new SimpleDateFormat("dd.MM.yyyy").format(cal.getTime())+".logs");
		fw.write(Data);
		
		fw.close();
	   
		
		
		
		}
		else
		{
		File	flt = new File(Settings.GetSett("PathLogs") + FileType+"_" + new SimpleDateFormat("dd.MM.yyyy").format(cal.getTime())+".logs");
			  PrintWriter out = new PrintWriter(new BufferedWriter(new FileWriter(flt)));
			  out.print(Data);
			  out.flush();
			  out.close();
		}
		
		if (br!=null)
			br.close();
		
		if (fr!=null)
			fr.close();
	
		
		}
	
	
}
