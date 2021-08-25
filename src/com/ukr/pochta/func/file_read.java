package com.ukr.pochta.func;

import java.io.BufferedReader;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStreamReader;

public class file_read {
	
	public static String main (String SQL_FILES, String Path) throws FileNotFoundException, IOException
	{
		

        File file = new File(Path + SQL_FILES);
      
        
        BufferedReader br = new BufferedReader (
            new InputStreamReader(
                new FileInputStream( file ), "UTF-8"
            )
        );
        String line = null;
        String line_to_re = "";
        while ((line = br.readLine()) != null) {

        	line_to_re = line_to_re + line; 
        }
      br.close();
		
		return line_to_re;
		
	}

}
