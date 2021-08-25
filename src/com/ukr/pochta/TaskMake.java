package com.ukr.pochta;

import java.io.FileNotFoundException;
import java.io.IOException;
import java.sql.SQLException;
import java.util.HashMap;
import java.util.concurrent.Executors;
import java.util.concurrent.TimeUnit;

import org.json.simple.JSONObject;
import org.json.simple.JSONValue;
import org.json.simple.parser.ParseException;

import com.ukr.pochta.func.Globals;
import com.ukr.pochta.func.Logs;
import com.ukr.pochta.func.MakeDebug;
import com.ukr.pochta.func.MakePost;
import com.ukr.pochta.func.SQLMake;
import com.ukr.pochta.func.Settings;

public class TaskMake extends Thread {
	
	public void run() {
    	

    	Executors.newSingleThreadScheduledExecutor().scheduleAtFixedRate(new Runnable() {
    	    public void run() {
    	   	
    	    	
    	    	 HashMap <String, Integer> StatusListFinal = new HashMap <String, Integer> ();
    	    	
    	    	try {
					SQLMake.MakeConnection();
					
					 JSONObject FinalStatusList =  SQLMake.main("GetFinalStatusList", null, 1 );
				
					   if (!FinalStatusList.get("result").equals("ok"))
				       {
							 
							
						 
				            	SQLMake.RollBack();
				            	SQLMake.ConnectionClose();
						
						 
						 return;
						 }
					   
					   
					   if (Globals.DEBUG)
			            	MakeDebug.Add("FinalStatusList: " + FinalStatusList);
				       
				  
				       
				       for (int i = 0; i<=((JSONObject)FinalStatusList.get("QueryData")).size()-1; i++)
				       {
				    	   
				    	   JSONObject StatusList = (JSONObject)((JSONObject)FinalStatusList.get("QueryData")).get(i);
				    	   
				    	   StatusListFinal.put(StatusList.get("name").toString(), 1);
				    	   
				    	   if (Globals.DEBUG)
				            	MakeDebug.Add("CheckData: " + StatusList);
				    	   
				       }
					   
    	    	
    	    	
    	    	   JSONObject GetTransacInfo =  SQLMake.main("TaskGetToCheck", null, 1 );
			        

			       
							       
			       if (!GetTransacInfo.get("result").equals("ok"))
			       {

			    	   if (Globals.DEBUG)
			            	MakeDebug.Add("Eroror GetTransacInfo: "+GetTransacInfo);
			    	   
			            	SQLMake.RollBack();
			            	SQLMake.ConnectionClose();
			            	
			            	new Logs("TaskMake", "Eroror GetTransacInfo: "+GetTransacInfo);
					
					 
					 return;
					 }
    	    	
			       if (Globals.DEBUG)
		            	MakeDebug.Add("GetTransacInfo: " + GetTransacInfo);
			       
			   
			       
			       for (int i = 0; i<=((JSONObject)GetTransacInfo.get("QueryData")).size()-1; i++)
			       {
			    	   
			    	   JSONObject CheckData = (JSONObject)((JSONObject)GetTransacInfo.get("QueryData")).get(i);
			    	   
			    	   if (Globals.DEBUG)
			            	MakeDebug.Add("CheckData: " + CheckData);
			    	   
			    	   
			   		JSONObject GetStatus = new JSONObject ();
					
					GetStatus.put("GetStatusByID", CheckData.get("TransferUID").toString());
					
					String STATUS_URL_RESULT = MakePost.main(Settings.GetSett("STATUS_URL"), GetStatus.toString());
					
					if (Globals.DEBUG)
						MakeDebug.Add("STATUS_URL_RESULT: " + STATUS_URL_RESULT);
					
					
					

					if (STATUS_URL_RESULT.equals("error"))
					{
						new Logs("TaskMake", "Get error in result for TransferUID: " + CheckData.get("TransferUID"));
						continue;
					
					}
					JSONObject AnswGet = null;
		            
		            try {
		            	AnswGet = (JSONObject) JSONValue.parseWithException(STATUS_URL_RESULT);
					} catch (ParseException e3) {
						e3.printStackTrace();
					}
		            
		            
		            if (AnswGet.get("status")==null)
		        	{
						new Logs("TaskMake", "Status is empty for TransferUID: " + CheckData.get("TransferUID")+". get answer: "+STATUS_URL_RESULT);
						continue;
					
					}
		            
		            if (Globals.DEBUG)
		            {
		            	MakeDebug.Add("status is ok. do next");
		            	MakeDebug.Add("StatusListFinal: " + StatusListFinal.get(AnswGet.get("status").toString()));

		            		MakeDebug.Add("AnswGet status: " + AnswGet.get("status").toString());
		            		MakeDebug.Add("send_status: " + CheckData.get("send_status").toString());
	                     	MakeDebug.Add("up_status: " + CheckData.get("up_status").toString());
		            	
		            }					
		          
		            
		            if (!AnswGet.get("status").toString().equals(CheckData.get("up_status").toString()))
		            {
		            	JSONObject InsData = new JSONObject();
		            	
		            	boolean need_upd_bal = false;
		            	
		            	  InsData.put("status_up", AnswGet.get("status"));
		            	  
		            	  if (AnswGet.get("status").toString().equals("DO"))
		            	  {
		            	  	InsData.put("status", 7);
		            	  	need_upd_bal = true;
		            	  	
		            	  	InsData.put("TransacType", "-");
		            	    InsData.put("type", "4");
		            	  	
		            	  }
		            	  	
		            	  if (AnswGet.get("status").toString().equals("OP") || AnswGet.get("status").toString().equals("EP"))
		            	  {
		            		  InsData.put("status", 6);
		            		  

		            		  
		            		  if (CheckData.get("send_status").toString().equals("5"))
		            		  {
		            			
		            			  need_upd_bal = true;
				            	  	
				            	    InsData.put("TransacType", "-");
				            	    InsData.put("type", "4");
		            			  
		            		  }

			            	  	
		            	  }
		            	  
		            	  if (AnswGet.get("status").toString().equals("DP"))
		            	  {
		            		  InsData.put("status", 8);
		            		  if (CheckData.get("send_status").toString().equals("7"))
		            		  {
		            		  
		            		  
			            	  InsData.put("TransacType", "+");
			            	  InsData.put("type", "5");
		            		  
			            	  	
			            	  	need_upd_bal = true;
		            		  }
		            	  }
		            	  
		            	  InsData.put("TRANSAC_ID", CheckData.get("ID"));
		            	  InsData.put("Amount", CheckData.get("Amount"));
		            	  InsData.put("company_id", CheckData.get("company_id"));
		            	  InsData.put("Currency", CheckData.get("ccy_id"));
		            	  InsData.put("AmountOpl", CheckData.get("AmountOpl"));
		            	  InsData.put("CurrencyOpl", CheckData.get("ccy_opl_id"));
		            	  
		            	  boolean need_opl_upd = false;
		            	  
		            	  if (!CheckData.get("ccy_opl_id").toString().equals(CheckData.get("ccy_id").toString()))
								need_opl_upd = true;
				            
		            	  SQLMake.AutoComit(false);
		            	  
				            
				            JSONObject UpdTransac =  SQLMake.main("UpdTransacStatus_Task", InsData, 3 );
		            	
				        	if (!UpdTransac.get("result").equals("ok"))
							{
							
								SQLMake.RollBack();
								
								new Logs("TaskMake", "SQL-error update UpdTransacStatus TransferUID: " + CheckData.get("TransferUID")+". get answer: "+STATUS_URL_RESULT);
								continue;
							}
				            
		          
		            
		             UpdTransac =  SQLMake.main("UpdTransacStatusUP_Task", InsData, 3 );
					
					if (!UpdTransac.get("result").equals("ok"))
					{
					
						SQLMake.RollBack();
						
						new Logs("TaskMake", "SQL-error update UpdTransacStatusUP TransferUID: " + CheckData.get("TransferUID")+". get answer: "+STATUS_URL_RESULT);
						continue;
					}
		            
					if (need_upd_bal)
					{
						
						
						
						JSONObject GetCurBalTask =  SQLMake.main("GetCurBalTask", InsData, 1 );
						
						if (Globals.DEBUG)
			               	MakeDebug.Add("GetCurBalTask: " + GetCurBalTask);
						
						if (!GetCurBalTask.get("result").equals("ok"))
						{
							
							SQLMake.RollBack();
							
							new Logs("TaskMake", "SQL-error update GetCurBalTask TransferUID: " + CheckData.get("TransferUID")+". get answer: "+STATUS_URL_RESULT);
							continue;
						}
						
						
						
						
					JSONObject CurBalUpd =  SQLMake.main("CurBalUpd", InsData, 3 );
					
					if (Globals.DEBUG)
		               	MakeDebug.Add("CurBalUpd: " + CurBalUpd);
					
					if (!CurBalUpd.get("result").equals("ok"))
					{
						
						SQLMake.RollBack();
						
						new Logs("TaskMake", "SQL-error update CurBalUpd TransferUID: " + CheckData.get("TransferUID")+". get answer: "+STATUS_URL_RESULT);
						continue;
					}
					 
			
					InsData.put("BAL", ((JSONObject)((JSONObject)GetCurBalTask.get("QueryData")).get(0)).get("amount") );
					InsData.put("last_upd", ((JSONObject)((JSONObject)GetCurBalTask.get("QueryData")).get(0)).get("last_upd") );
					
					
					
					
					
					JSONObject CurBalUpd_jn =  SQLMake.main("CurBalUpd_jn", InsData, 3 );
					
					if (Globals.DEBUG)
		               	MakeDebug.Add("CurBalUpd_jn: " + CurBalUpd_jn);
					
					if (!CurBalUpd_jn.get("result").equals("ok"))
					{
						
						SQLMake.RollBack();
						
						new Logs("TaskMake", "SQL-error update CurBalUpd TransferUID: " + CheckData.get("TransferUID")+". get answer: "+STATUS_URL_RESULT);
						continue;
					}
					
					
					if (need_opl_upd) {
						
						
					       JSONObject InsDataOpl = new JSONObject ();
					       
					       InsDataOpl.put("Currency", CheckData.get("ccy_opl_id"));
					       InsDataOpl.put("company_id", CheckData.get("company_id"));
					       
					       
					       GetCurBalTask =  SQLMake.main("GetCurBalTask", InsDataOpl, 1 );
							
							if (Globals.DEBUG)
				               	MakeDebug.Add("GetCurBalTask: " + GetCurBalTask);
							
							if (!GetCurBalTask.get("result").equals("ok"))
							{
								
								SQLMake.RollBack();
								
								new Logs("TaskMake", "SQL-error update GetCurBalTask TransferUID: " + CheckData.get("TransferUID")+". get answer: "+STATUS_URL_RESULT);
								continue;
							}
					
							
							InsData.put("BAL", ((JSONObject)((JSONObject)GetCurBalTask.get("QueryData")).get(0)).get("amount") );
							InsData.put("last_upd", ((JSONObject)((JSONObject)GetCurBalTask.get("QueryData")).get(0)).get("last_upd") );
							InsData.put("Currency", CheckData.get("ccy_opl_id") );
							
					
					JSONObject CurBalUpdOpl =  SQLMake.main("CurBalUpdOpl", InsData, 3 );
					
					if (Globals.DEBUG)
		               	MakeDebug.Add("CurBalUpdOpl: " + CurBalUpdOpl);
			        
				       if (!CurBalUpdOpl.get("result").equals("ok"))
				       {
							 
				    	   SQLMake.RollBack();
							
							new Logs("TaskMake", "SQL-error update CurBalUpdOpl TransferUID: " + CheckData.get("TransferUID")+". get answer: "+STATUS_URL_RESULT);
							continue;
						 }
					
				
						
						
				       InsData.put("Amount", CheckData.get("AmountOpl"));
				       
				       
				       CurBalUpd_jn =  SQLMake.main("CurBalUpd_jn", InsData, 3 );
						
						if (Globals.DEBUG)
			               	MakeDebug.Add("CurBalUpd_jn: " + CurBalUpd_jn);
						
						if (!CurBalUpd_jn.get("result").equals("ok"))
						{
							
							SQLMake.RollBack();
							
							new Logs("TaskMake", "SQL-error update CurBalUpd TransferUID: " + CheckData.get("TransferUID")+". get answer: "+STATUS_URL_RESULT);
							continue;
						}
				       
					
					}
					
					}
					
					
					SQLMake.Commit();
					
					
					 SQLMake.AutoComit(true);
		            
		            }
					 
		            
		            if (Globals.DEBUG)
		               	MakeDebug.Add("go to next loop");
			    	   
			       }
    	    	
    	    	
			       SQLMake.ConnectionClose();
			       
			       
    	    	} catch (ClassNotFoundException | SQLException e) {
					e.printStackTrace();
				} catch (FileNotFoundException e) {
					e.printStackTrace();
				} catch (IOException e) {
					e.printStackTrace();
				}
    	    	
			}

}, 0, Integer.valueOf(Settings.GetSett("TaskPeriod")), TimeUnit.SECONDS);
    	
    	

          } 

}
