package com.ukr.pochta.func;

import java.io.File;
import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.nio.file.StandardOpenOption;



public class MakeDebug {
	
	public static void Add (String data)
	{
		System.out.println(data);
		
		String filePath = Settings.GetSett("debug_path")+"DEBUG";
		data+="\n";
      
        try {
        	if (new File(filePath).exists())
        		Files.write(Paths.get(filePath), data.getBytes(), StandardOpenOption.APPEND);
        	else
        		Files.write(Paths.get(filePath), data.getBytes(), StandardOpenOption.CREATE_NEW);
        }
        catch (IOException e) {
            System.out.println(e);
        }
		
	}

}
