package com.ukr.pochta.func;

import java.io.FileNotFoundException;
import java.io.IOException;

import org.json.simple.JSONArray;
import org.json.simple.parser.JSONParser;
import org.json.simple.parser.ParseException;

public class ParsJson {

	public static JSONArray Result_data (String data) throws FileNotFoundException, IOException
	{
		JSONParser parser = new JSONParser();      	
    	Object obj = null;
		try {
			obj = parser.parse(data);
		} catch (ParseException e1) {
			obj = null;
		}
    	return (JSONArray) obj;
	}
	
}
