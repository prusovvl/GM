package com.ukr.pochta.HttpsServer;

import java.io.BufferedReader;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.io.UnsupportedEncodingException;
import java.nio.charset.StandardCharsets;
import java.security.InvalidKeyException;
import java.security.KeyFactory;
import java.security.NoSuchAlgorithmException;
import java.security.PublicKey;
import java.security.Signature;
import java.security.SignatureException;
import java.security.cert.CertificateException;
import java.security.cert.CertificateFactory;
import java.security.spec.InvalidKeySpecException;
import java.security.spec.X509EncodedKeySpec;
import java.sql.SQLException;

import org.apache.commons.codec.binary.Base64;
import org.json.simple.JSONObject;
import org.json.simple.JSONValue;
import org.json.simple.parser.ParseException;

import com.sun.net.httpserver.HttpExchange;
import com.sun.net.httpserver.HttpHandler;
import com.ukr.pochta.func.Globals;
import com.ukr.pochta.func.Logs;
import com.ukr.pochta.func.MakeDebug;
import com.ukr.pochta.func.MakePost;
import com.ukr.pochta.func.SQLMake;
import com.ukr.pochta.func.Settings;

public class AddTransac implements HttpHandler {

	@Override
	public void handle(HttpExchange he) throws IOException {

		Thread RootTh = new Thread(new Runnable() {
		    public void run() {

		    	  
		    	InputStreamReader isr = null;
		    	StringBuilder sb = null;
				try {
					isr = new InputStreamReader(he.getRequestBody(),"utf-8");
					BufferedReader br = new BufferedReader(isr);
					int b;
			    	sb = new StringBuilder(512);
			    	while ((b = br.read()) != -1) {
			    		sb.append((char) b);
			    	}

			    	br.close();
			    	isr.close();
					
				} catch (UnsupportedEncodingException e2) {

					e2.printStackTrace();
				} catch (IOException e) {

					e.printStackTrace();
				}
		    	
	      
	            if (Globals.DEBUG)
					MakeDebug.Add("POSTReqParam: " + sb.toString());
	            
	            if (sb.toString().trim().equals(""))
	            {
	            	JSONObject GetError = new JSONObject();
	            	GetError.put("code", 100);
	            	GetError.put("msg", "Помилка аналізу запиту");
	            	
	            	try {
						MakeSend (he, GetError.toString());
					} catch (IOException e) {

						e.printStackTrace();
					}
	            
	            return;
	            }
	            
	            JSONObject getPostData = null;
	            
	            try {
					getPostData = (JSONObject) JSONValue.parseWithException(sb.toString());
				} catch (ParseException e3) {

					e3.printStackTrace();
				}
	            
	      
	            JSONObject Result = new JSONObject ();
	            
		            if (Globals.DEBUG)
		            	MakeDebug.Add("getPostData: " + getPostData);
		            
					if (getPostData.get("edrpou")==null || 
			        		getPostData.get("TransferUID")==null ||	
			        		getPostData.get("PostOfficeZip")==null ||	
			        		getPostData.get("WorkPlace")==null ||	
			        		getPostData.get("TransferID")==null ||	
			        		getPostData.get("sFName")==null ||	
			        		getPostData.get("sLName")==null ||	
			        		getPostData.get("sСountry")==null ||	
			        		getPostData.get("rFName")==null ||	
			        		getPostData.get("rLName")==null ||	
			        		getPostData.get("rStreet")==null ||	
			        		getPostData.get("rCity")==null ||	
			        		getPostData.get("rZip")==null ||	
			        		getPostData.get("rPhone")==null ||	
			        		getPostData.get("Amount")==null ||	
			        		getPostData.get("Currency")==null ||	
			        		getPostData.get("AmountOpl")==null ||	
			        		getPostData.get("CurrencyOpl")==null ||	
			        		getPostData.get("OrderDate")==null ||	
			        		getPostData.get("OrderTime")==null ||	
			        		getPostData.get("Category")==null ||	
			        		getPostData.get("Status")==null ||	
			        		getPostData.get("kgp")==null ||	
							getPostData.get("kgp_num")==null
			        		)    
			        {
						
						if (Globals.DEBUG)
							MakeDebug.Add("Get null value");
			        	
			        	JSONObject GetError = new JSONObject();
		            	GetError.put("code", 200);
		            	GetError.put("msg", "Заповнені не всі облв'язкові поля");
				 
				 try {
						MakeSend (he, GetError.toString());
							} catch (IOException e) {

								e.printStackTrace();
							}
						 
						 return;
						 }
					
					boolean need_opl_upd = false;
		            
					if (getPostData.get("AmountOpl")==null)
							getPostData.put ("AmountOpl", getPostData.get("Amount"));
					
					
					if (getPostData.get("CurrencyOpl")==null)
						getPostData.put ("CurrencyOpl", getPostData.get("Currency"));		
					
					if (!getPostData.get("CurrencyOpl").toString().equals(getPostData.get("Currency").toString()))
						need_opl_upd = true;
		            
		        if (String.valueOf(getPostData.get("edrpou")).trim().equals("") || 
		        		String.valueOf(getPostData.get("TransferUID")).trim().equals("") ||	
		        		String.valueOf(getPostData.get("PostOfficeZip")).trim().equals("") ||	
		        		String.valueOf(getPostData.get("WorkPlace")).trim().equals("") ||	
		        		String.valueOf(getPostData.get("TransferID")).trim().equals("") ||	
		        		String.valueOf(getPostData.get("sFName")).trim().equals("") ||	
		        		String.valueOf(getPostData.get("sLName")).trim().equals("") ||	
		        		String.valueOf(getPostData.get("sСountry")).trim().equals("") ||	
		        		String.valueOf(getPostData.get("rFName")).trim().equals("") ||	
		        		String.valueOf(getPostData.get("rLName")).trim().equals("") ||	
		        		String.valueOf(getPostData.get("rStreet")).trim().equals("") ||	
		        		String.valueOf(getPostData.get("rCity")).trim().equals("") ||	
		        		String.valueOf(getPostData.get("rZip")).trim().equals("") ||	
		        		String.valueOf(getPostData.get("rPhone")).trim().equals("") ||	
		        		String.valueOf(getPostData.get("Amount")).trim().equals("") ||	
		        		String.valueOf(getPostData.get("Currency")).trim().equals("") ||	
		        		String.valueOf(getPostData.get("AmountOpl")).trim().equals("") ||	
		        		String.valueOf(getPostData.get("CurrencyOpl")).trim().equals("") ||	
		        		String.valueOf(getPostData.get("OrderDate")).trim().equals("") ||	
		        		String.valueOf(getPostData.get("OrderTime")).trim().equals("") ||	
		        		String.valueOf(getPostData.get("Category")).trim().equals("") ||	
		        		String.valueOf(getPostData.get("Status")).trim().equals("") ||	
		        		String.valueOf(getPostData.get("kgp")).trim().equals("") ||	
		        	String.valueOf(getPostData.get("kgp_num")).trim().equals("")
		        		)    
		        {
		        	
					if (Globals.DEBUG)
						MakeDebug.Add("Get empty value");
		        	
		        	JSONObject GetError = new JSONObject();
	            	GetError.put("code", 200);
	            	GetError.put("msg", "Заповнені не всі обов'язкові поля");
			 
			 try {
					MakeSend (he, GetError.toString());
						} catch (IOException e) {

							e.printStackTrace();
						}
					 
					 return;
					 }
		            
		            

		            try {
						SQLMake.MakeConnection();
						
						Result =  SQLMake.main("GetCompany", getPostData, 1 );
						
						SQLMake.ConnectionClose();

						
						 if (Globals.DEBUG)
				            	MakeDebug.Add("Result: " + Result);
						 
						 
						 if (!Result.get("result").equals("ok"))
						 {
							 
							 JSONObject GetError = new JSONObject();
				            	GetError.put("code", 101);
				            	GetError.put("msg", "Помилка виконання запиту");
						 
						
								MakeSend (he, GetError.toString());
						
						 
						 return;
						 }
						 
						 if (Globals.DEBUG)
				            	MakeDebug.Add("Get Public key: " + ((JSONObject)((JSONObject)Result.get("QueryData")).get(0)).get("public_key").toString().replace("\n", "").replace("\r", ""));
						 
						 
						 byte[] publicBytes = Base64.decodeBase64(((JSONObject)((JSONObject)Result.get("QueryData")).get(0)).get("public_key").toString().replace("\n", "").replace("\r", "").getBytes("utf-8"));
						 X509EncodedKeySpec keySpec = new X509EncodedKeySpec(publicBytes);
						 KeyFactory keyFactory = KeyFactory.getInstance("RSA");
						 PublicKey pubKey = keyFactory.generatePublic(keySpec);
						 
						 CertificateFactory factory = CertificateFactory.getInstance("X.509");
						 
						 Signature sig = Signature.getInstance("SHA256withRSA");
					        sig.initVerify(pubKey);
					        sig.update((getPostData.get("edrpou").toString()+
					        		getPostData.get("TransferUID").toString()+		
					        		getPostData.get("PostOfficeZip").toString()+		
					        		getPostData.get("WorkPlace").toString()+		
					        		getPostData.get("TransferID").toString()+		
					        		getPostData.get("sFName").toString()+		
					        		getPostData.get("sLName").toString()+		
					        		getPostData.get("sСountry").toString()+		
					        		getPostData.get("rFName").toString()+		
					        		getPostData.get("rLName").toString()+		
					        		getPostData.get("rStreet").toString()+		
					        		getPostData.get("rCity").toString()+			
					        		getPostData.get("rZip").toString()+		
					        		getPostData.get("rPhone").toString()+		
					        		getPostData.get("Amount").toString()+		
					        		getPostData.get("Currency").toString()+		
					        		getPostData.get("AmountOpl").toString()+		
					        		getPostData.get("CurrencyOpl").toString()+		
					        		getPostData.get("OrderDate").toString()+		
					        		getPostData.get("OrderTime").toString()+		
					        		getPostData.get("Category").toString()+		
					        		getPostData.get("Status").toString()+		
					        		getPostData.get("kgp").toString()+		
					        		getPostData.get("kgp_num").toString()).getBytes("UTF-8")				        		
					        		);

					        if (!sig.verify( Base64.decodeBase64( getPostData.get("sign").toString().getBytes("UTF-8"))))
					        {
					        	
					        	 JSONObject GetError = new JSONObject();
					            	GetError.put("code", 201);
					            	GetError.put("msg", "Помилка перевірки підпису");
							 
						
									MakeSend (he, GetError.toString());
						
					        	
					        	return;
					        }
					        
					        if (getPostData.get("sCity")==null)  getPostData.put("sCity", "");
					        if (getPostData.get("sStreet")==null)  getPostData.put("sStreet", "");
					        if (getPostData.get("rMName")==null)  getPostData.put("rMName", "");
						
					        
					        SQLMake.MakeConnection();
							
					        SQLMake.AutoComit(false);
					        
					     
					        
					        getPostData.remove("sign");
					        
					       JSONObject GetDopData =  SQLMake.main("GetDopData", getPostData, 1 );
					        
					       if (!GetDopData.get("result").equals("ok"))
					       {
								 
								 JSONObject GetError = new JSONObject();
					            	GetError.put("code", 202);
					            	GetError.put("msg", "Помилка визначення PostOfficeName");
							 
					            	SQLMake.RollBack();
					            	SQLMake.ConnectionClose();
							
									MakeSend (he, GetError.toString());
							
							 
							 return;
							 }
					       
					       
					       
					       JSONObject InsData = getPostData;
					       InsData.put("company_id", ((JSONObject)((JSONObject)Result.get("QueryData")).get(0)).get("company_id"));
					       
					       
					       
					       JSONObject GetCurBal =  SQLMake.main("GetCurBal", InsData, 1 );
					        
					       if (Globals.DEBUG)
				            	MakeDebug.Add("GetCurBal: " + GetCurBal);
					       
									       
					       if (!GetCurBal.get("result").equals("ok"))
					       {
								 
								 JSONObject GetError = new JSONObject();
					            	GetError.put("code", 204);
					            	GetError.put("msg", "Помилка визначення поточного балансу");
							 
					            	SQLMake.RollBack();
					            	SQLMake.ConnectionClose();
							
									MakeSend (he, GetError.toString());
							
							 
							 return;
							 }
					    	   
					       
					       if ( Integer.valueOf( ((JSONObject)((JSONObject)GetCurBal.get("QueryData")).get(0)).get("amount").toString()) < Integer.valueOf(getPostData.get("Amount").toString()) )
					       {
					    	   
								 JSONObject GetError = new JSONObject();
					            	GetError.put("code", 205);
					            	GetError.put("msg", "Помилка балансу. Валюта UAH");
							 
					            	SQLMake.RollBack();
					            	SQLMake.ConnectionClose();
							
									MakeSend (he, GetError.toString());
							
							 
							 return;
					    	   
					       }
					       
					       
					       if (need_opl_upd) {
					       
					       JSONObject GetCurBalOpl =  SQLMake.main("GetCurBalOpl", InsData, 1 );
					        
					       if (!GetCurBalOpl.get("result").equals("ok"))
					       {
								 
								 JSONObject GetError = new JSONObject();
					            	GetError.put("code", 204);
					            	GetError.put("msg", "Помилка визначення поточного балансу");
							 
					            	SQLMake.RollBack();
					            	SQLMake.ConnectionClose();
							
									MakeSend (he, GetError.toString());
							
							 
							 return;
							 }
					    	   
					       
					       if ( Integer.valueOf( ((JSONObject)((JSONObject)GetCurBalOpl.get("QueryData")).get(0)).get("amount").toString()) < Integer.valueOf(getPostData.get("AmountOpl").toString()) )
					       {
					    	   
								 JSONObject GetError = new JSONObject();
					            	GetError.put("code", 205);
					            	GetError.put("msg", "Помилка балансу. Валюта "+getPostData.get("CurrencyOpl").toString());
							 
					            	SQLMake.RollBack();
					            	SQLMake.ConnectionClose();
							
									MakeSend (he, GetError.toString());
							
							 
							 return;
					    	   
					       }
					       
					       
					       }
					    	   
					      
					        
					        getPostData.remove("edrpou");
					        
					        getPostData.put("PostOfficeName", ((JSONObject)((JSONObject)GetDopData.get("QueryData")).get(0)).get("name"));
					        
							long leftLimit =  100000000000L;
						    long rightLimit = 999999999999L;
						    long generatedLong = leftLimit + (long) (Math.random() * (rightLimit - leftLimit));
					        
					        InsData.put("TRANSAC_ID", String.valueOf(generatedLong));
					      
					        
							Result =  SQLMake.main("AddTransac", InsData, 3 );
							
							 if (Globals.DEBUG)
					            	MakeDebug.Add("AddTransac Result: " + Result);
							
							if (!Result.get("result").equals("ok"))
							  {
								 
								 JSONObject GetError = new JSONObject();
					            	GetError.put("code", 203);
					            	GetError.put("msg", "Помилка збереження транзакції");
							 
					            	SQLMake.RollBack();
					            	SQLMake.ConnectionClose();
							
									MakeSend (he, GetError.toString());
							
							 
							 return;
							  }
							

							
						String TRANSFER_URL_RESULT = MakePost.main(Settings.GetSett("TRANSFER_URL"), getPostData.toString());
							
						if (Globals.DEBUG)
							MakeDebug.Add("TRANSFER_URL_RESULT: " + TRANSFER_URL_RESULT);
						
						if (TRANSFER_URL_RESULT.equals("error"))
						{
							JSONObject GetError = new JSONObject();
							GetError.put("code", 207);
							GetError.put("msg", "Помилка реєстрації транзакції");
							
							SQLMake.RollBack();
							SQLMake.ConnectionClose();
							
							MakeSend (he, GetError.toString());
							
							return;
						}
						
						
						
						  JSONObject AnswGetAddTransac = null;
				            
				            try {
				            	AnswGetAddTransac = (JSONObject) JSONValue.parseWithException(TRANSFER_URL_RESULT);
							} catch (ParseException e3) {
								// TODO Auto-generated catch block
								e3.printStackTrace();
							}
				            
				            if (AnswGetAddTransac.get("status")==null)
				            {
				            	
				            	InsData.put("status", 4);
								
								JSONObject UpdTransac =  SQLMake.main("UpdTransacStatus", InsData, 3 );
								
								if (!UpdTransac.get("result").equals("ok"))
								{
									
									JSONObject GetError = new JSONObject();
									GetError.put("code", 208);
									GetError.put("msg", "Помилка оновлення статусу");
									
									SQLMake.RollBack();
									SQLMake.ConnectionClose();
									
									MakeSend (he, GetError.toString());
									
									
									return;
								}
				            	
				            	
				            	new Logs("UpStatus", "Error get status. [Answ: "+TRANSFER_URL_RESULT+"]");
				            	
				            	JSONObject GetError = new JSONObject();
								GetError.put("code", 207);
								GetError.put("msg", "Помилка реєстрації транзакції");
								GetError.put("ID", generatedLong);
								
								SQLMake.Commit();
								SQLMake.ConnectionClose();
								
								MakeSend (he, GetError.toString());
				            	
								return;
				            }
						
						
						if (Integer.valueOf(AnswGetAddTransac.get("status").toString())!=11)
						{
							
							
						  	InsData.put("status", 4);
							
							JSONObject UpdTransac =  SQLMake.main("UpdTransacStatus", InsData, 3 );
							
							if (!UpdTransac.get("result").equals("ok"))
							{
								
								JSONObject GetError = new JSONObject();
								GetError.put("code", 208);
								GetError.put("msg", "Помилка оновлення статусу");
								
								SQLMake.RollBack();
								SQLMake.ConnectionClose();
								
								MakeSend (he, GetError.toString());
								
								
								return;
							}
			            	
			            	
			            	new Logs("UpStatus", "Error get status. [Answ: "+TRANSFER_URL_RESULT+"]");
			            	
			            	JSONObject GetError = new JSONObject();
							GetError.put("code", 207);
							GetError.put("msg", "Помилка реєстрації транзакції");
							GetError.put("ID", generatedLong);
							
							SQLMake.Commit();
							SQLMake.ConnectionClose();
							
							MakeSend (he, GetError.toString());
							
							return;
							
						}
							
						
						
						
						
						
						
						
						
						
						
						
						
						JSONObject GetStatus = new JSONObject ();
						
						GetStatus.put("GetStatusByID", getPostData.get("TransferUID"));
						
						String STATUS_URL_RESULT = MakePost.main(Settings.GetSett("STATUS_URL"), GetStatus.toString());
						
						if (Globals.DEBUG)
							MakeDebug.Add("STATUS_URL_RESULT: " + STATUS_URL_RESULT);
						
						if (STATUS_URL_RESULT.equals("error"))
						{
							InsData.put("status", 4);
							
							JSONObject UpdTransac =  SQLMake.main("UpdTransacStatus", InsData, 3 );
							
							if (!UpdTransac.get("result").equals("ok"))
							{
								
								JSONObject GetError = new JSONObject();
								GetError.put("code", 208);
								GetError.put("msg", "Помилка оновлення статусу");
								
								SQLMake.RollBack();
								SQLMake.ConnectionClose();
								
								MakeSend (he, GetError.toString());
								
								
								return;
							}
				            
							
							
							JSONObject GetError = new JSONObject();
							GetError.put("code", 207);
							GetError.put("msg", "Помилка реєстрації транзакції");
							GetError.put("ID", generatedLong);
							
							SQLMake.Commit();
							SQLMake.ConnectionClose();
							
							MakeSend (he, GetError.toString());
							
							return;
						}
						
						
						
						  JSONObject AnswGet = null;
				            
				            try {
				            	AnswGet = (JSONObject) JSONValue.parseWithException(STATUS_URL_RESULT);
							} catch (ParseException e3) {
								// TODO Auto-generated catch block
								e3.printStackTrace();
							}
				            
				            if (AnswGet.get("status")==null)
				            {
				            	
				            	InsData.put("status", 4);
								
								JSONObject UpdTransac =  SQLMake.main("UpdTransacStatus", InsData, 3 );
								
								if (!UpdTransac.get("result").equals("ok"))
								{
									
									JSONObject GetError = new JSONObject();
									GetError.put("code", 208);
									GetError.put("msg", "Помилка оновлення статусу");
									
									SQLMake.RollBack();
									SQLMake.ConnectionClose();
									
									MakeSend (he, GetError.toString());
									
									
									return;
								}
				            	
				            	
				            	new Logs("UpStatus", "Error get status. [Answ: "+STATUS_URL_RESULT+"]");
				            	
				            	JSONObject GetError = new JSONObject();
								GetError.put("code", 207);
								GetError.put("msg", "Помилка реєстрації транзакції");
								GetError.put("ID", generatedLong);
								
								SQLMake.Commit();
								SQLMake.ConnectionClose();
								
								MakeSend (he, GetError.toString());
				            	
				            }
						
				            InsData.put("status_up", AnswGet.get("status"));
				            
				            InsData.put("message", AnswGetAddTransac.get("message").toString());
				            
				            JSONObject UpdTransac =  SQLMake.main("UpdTransacStatusUP", InsData, 3 );
							
							if (!UpdTransac.get("result").equals("ok"))
							{
								
								JSONObject GetError = new JSONObject();
								GetError.put("code", 208);
								GetError.put("msg", "Помилка оновлення статусу");
								
								SQLMake.RollBack();
								SQLMake.ConnectionClose();
								
								MakeSend (he, GetError.toString());
								
								
								return;
							}
				            
							if (AnswGet.get("status").toString().equals("VO") || AnswGet.get("status").toString().equals("DO"))
							{
							JSONObject CurBalUpd =  SQLMake.main("WaitingBal", InsData, 3 );
							
							if (!CurBalUpd.get("result").equals("ok"))
							{
								
								JSONObject GetError = new JSONObject();
								GetError.put("code", 206);
								GetError.put("msg", "Помилка оновлення балансу. Валюта UAH");
								
								SQLMake.RollBack();
								SQLMake.ConnectionClose();
								
								MakeSend (he, GetError.toString());
								
								
								return;
							}
							 
							
							if (need_opl_upd) {
							
							
							JSONObject CurBalUpdOpl =  SQLMake.main("WaitingBalOpl", InsData, 3 );
					        
						       if (!CurBalUpdOpl.get("result").equals("ok"))
						       {
									 
									 JSONObject GetError = new JSONObject();
						            	GetError.put("code", 206);
						            	GetError.put("msg", "Помилка оновлення балансу. Валюта "+getPostData.get("CurrencyOpl").toString());
								 
						            	SQLMake.RollBack();
						            	SQLMake.ConnectionClose();
								
										MakeSend (he, GetError.toString());
								
								 
								 return;
								 }
							
							
							}
							
							}
							
							
							SQLMake.Commit();
							
							
							 SQLMake.AutoComit(true);
							SQLMake.ConnectionClose();
					        
					        
						
					        JSONObject Answer = new JSONObject();
					        Answer.put("code", 0);
					        Answer.put("TRANSAC_ID", generatedLong);
			            	
					 
					        
					        
					       
								MakeSend (he, Answer.toString());
						
					        
					        
					        
					        
					        
					} catch (ClassNotFoundException | SQLException e) {
						// TODO Auto-generated catch block
						e.printStackTrace();
					} catch (FileNotFoundException e) {
						// TODO Auto-generated catch block
						e.printStackTrace();
					} catch (IOException e) {
						// TODO Auto-generated catch block
						e.printStackTrace();
					} catch (NoSuchAlgorithmException e) {
						// TODO Auto-generated catch block
						e.printStackTrace();
					} catch (InvalidKeySpecException e) {
						// TODO Auto-generated catch block
						e.printStackTrace();
					} catch (CertificateException e1) {
						// TODO Auto-generated catch block
						e1.printStackTrace();
					} catch (InvalidKeyException e1) {
						// TODO Auto-generated catch block
						e1.printStackTrace();
					} catch (SignatureException e) {
						// TODO Auto-generated catch block
						e.printStackTrace();
					}
		    		
					
                
		    }      
		    });
		RootTh.start();

	}
	
	private static void MakeSend (HttpExchange he, String response) throws IOException
	{
		
		he.getResponseHeaders().set("Content-Type", "application/json; charset=UTF-8");
			
		   byte[] SendBytes = response.getBytes(StandardCharsets.UTF_8);
		    he.sendResponseHeaders(200, SendBytes.length);
		  
		    OutputStream os = he.getResponseBody();
		    os.write(SendBytes);
		    os.close();
		
	}

}
