package com.ukr.pochta.func;

import java.io.FileNotFoundException;
import java.io.IOException;
import java.sql.CallableStatement;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.ResultSetMetaData;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.HashMap;
import java.util.Map;

import org.json.simple.JSONObject;


public class SQLMake {
	private static String Status = "Disconnect"; 
	private static Connection connection = null;
	private static ResultSet rs = null;
	private static int rs1 = 0;
	private static CallableStatement cs = null;
	private static PreparedStatement ps = null;
	
	public static void MakeConnection () throws SQLException, ClassNotFoundException
	{
		
		Status = "Connect";
		
	
		String username = Settings.GetSett("BaseLogin");
		String password = Settings.GetSett("BasePass");
		String serverName = Settings.GetSett("BaseServerName");
		String mydatabase = Settings.GetSett("BaseSelect");
		String url = "jdbc:mysql://" + serverName + "/" + mydatabase + "?useUnicode=true&characterEncoding=utf8&allowMultiQueries=true";
		
		String driverName = "com.mysql.jdbc.Driver"; 

		Class.forName(driverName);
		
		connection = DriverManager.getConnection(url, username, password);
	}
	
	public static Connection GetConnection()
	{
		
		
		
		return connection;
	}
	

	

	public static JSONObject main (String command, HashMap <String, String> Data, int QueryType) throws SQLException, FileNotFoundException, IOException
	{
		
		
		JSONObject res=null;

		
		JSONObject ResultMake = new JSONObject ();
		
				String Get_SQL = "";
				
				Get_SQL = file_read.main (command+".sql", Settings.GetSett("SQL_HOME"));
		
				if (Data!=null)
				{
					Object[] getKeys = Data.keySet().toArray();
					
					for (int i=0; i<=getKeys.length-1; i++)
					{
						
						
						if (Globals.DEBUG)
						{
							MakeDebug.Add("Try replace: " + getKeys [i]);
			            	MakeDebug.Add("to: " + String.valueOf(Data.get(getKeys [i])) );
						}
						
						Get_SQL = Get_SQL.replace("${"+getKeys [i].toString()+"}", String.valueOf(Data.get(getKeys [i])));
					}
				}
				
				
				if (Globals.DEBUG)
					MakeDebug.Add("Get_SQL: " + Get_SQL);
				

				try {
				if (QueryType==1 || QueryType==3)
					ps = GetConnection().prepareStatement(Get_SQL);
				
				
				
				if (QueryType==2)
					cs = GetConnection().prepareCall(Get_SQL);
				
				
				
				
				if (QueryType == 3)
				{
					try {

					ps.executeUpdate();
					ResultMake.put ("result", "ok");

					}
					catch (Exception ex) {

						ResultMake.put ("result", "sql_err");
						if (ps!=null)
							PrintStackTraceLog.main(command, ex, ps.toString());
						else
							PrintStackTraceLog.main(command, ex, "NULL");
						
						return ResultMake;
						
						}
				}
				
				if (QueryType == 1 || QueryType == 2)
				{
					
					
					try {
						
						if (QueryType == 1) {	
							
							try {

							rs = ps.executeQuery();
							
							ResultMake.put ("result", "ok");

							}
							catch (Exception ex1) {

								ResultMake.put ("result", "sql_err");
								
								if (ps!=null)
									PrintStackTraceLog.main(command+" :", ex1, ps.toString()); 
								else
									PrintStackTraceLog.main(command+" :", ex1, "NULL");
								
								return ResultMake;
							}
							
						
							
							}
						
						if (QueryType == 2)	
							{
							try {

							cs.execute();
							ResultMake.put ("result", "ok");

							}
							catch (Exception ex1) {

								
								ResultMake.put ("result", "sql_err");
								if (cs!=null)
									PrintStackTraceLog.main(command+" :", ex1, cs.toString()); 
								else
									PrintStackTraceLog.main(command+" :", ex1, "NULL"); 
								
								return ResultMake;
								}
							rs = cs.getResultSet();
							}
				
					int z=0;
					 ResultSetMetaData rsmd = rs.getMetaData();
					    rs.last();
			
					    ResultMake.put ("result", "ok");
					       
					   
					   
				if (rs.getRow()>0)
				{
					
					rs.first();

					JSONObject QueryData = new JSONObject();
					JSONObject QueryDataAll = new JSONObject();
					
					rs.beforeFirst();
					while (rs.next()) {
						
						for (int i = 1; i<=rsmd.getColumnCount(); i++)
						{
							
							 QueryData.put(rsmd.getColumnName(i), rs.getString(rsmd.getColumnName(i)));
						}	 
						QueryDataAll.put (z, QueryData);		 
						 z++;
						 QueryData = new JSONObject();
					}
					ResultMake.put("QueryData", QueryDataAll);
				
					
				
				}
				else
					ResultMake.put("QueryData", null);
				
				
				
					}
					catch (Exception ex) {
						if (ps!=null)
							PrintStackTraceLog.main(command, ex, ps.toString());
						else
							PrintStackTraceLog.main(command, ex, "NULL");
						
						
						
						ResultMake.put ("result", "sql_err");
												
						ErrLogs.main("SQL", ex.toString(), ex.getStackTrace()); 
						
						return ResultMake;
						
						}
				
				}
	
				
				
				
			}
			
			catch(SQLException sqlEx){
				
				sqlEx.printStackTrace();
				if (ps!=null)
					PrintStackTraceLog.main(command, sqlEx, ps.toString());
				else
					PrintStackTraceLog.main(command, sqlEx, "NULL");
				
				return ResultMake;
				
				}
				
				finally{
				    
					
				   }
		
		
		
			
		return ResultMake;
				
	}
	
	public static void AutoComit (boolean WhatDo) throws SQLException
	{
		if (WhatDo==true) GetConnection().prepareStatement("set autocommit = 1;").execute();
		else			  GetConnection().prepareStatement("set autocommit = 0;").execute();
	}
	
	
	
		
	public static void Commit() throws SQLException
	{
		GetConnection().prepareStatement("commit;").execute();	
	}
	
	public static void RollBack() throws SQLException
	{
		GetConnection().prepareStatement("ROLLBACK;").execute();
	}

	public static void ConnectionClose() throws SQLException
	{
		Status = "Disconnect";
		
		if(GetConnection()!=null) GetConnection().close();
	    if (rs!=null) rs.close ();
	    if (ps!=null) ps.close();
	    if (cs!=null) cs.close();
	}
	

}
