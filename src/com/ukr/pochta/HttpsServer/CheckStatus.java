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
import com.ukr.pochta.ref.UPStatus;

public class CheckStatus implements HttpHandler {

	@Override
	public void handle(HttpExchange he) throws IOException {
		// TODO Auto-generated method stub
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
					// TODO Auto-generated catch block
					e2.printStackTrace();
				} catch (IOException e) {
					// TODO Auto-generated catch block
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
						// TODO Auto-generated catch block
						e.printStackTrace();
					}
	            
	            return;
	            }
	            
	            JSONObject getPostData = null;
	            
	            try {
					getPostData = (JSONObject) JSONValue.parseWithException(sb.toString());
				} catch (ParseException e3) {
					// TODO Auto-generated catch block
					e3.printStackTrace();
				}
	            
	      
	            JSONObject Result = new JSONObject ();
	            
		            if (Globals.DEBUG)
		            	MakeDebug.Add("getPostData: " + getPostData);
		            
					if (getPostData.get("edrpou")==null || 
			        		getPostData.get("TRANSAC_ID")==null ||	
			        		getPostData.get("OrderDate")==null 
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
								// TODO Auto-generated catch block
								e.printStackTrace();
							}
						 
						 return;
						 }
					
	
		            
		        if (String.valueOf(getPostData.get("edrpou")).trim().equals("") || 
		        		String.valueOf(getPostData.get("TRANSAC_ID")).trim().equals("") ||	
		        		String.valueOf(getPostData.get("OrderDate")).trim().equals("") 
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
							// TODO Auto-generated catch block
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
					        		getPostData.get("TRANSAC_ID").toString()+		
					        		getPostData.get("OrderDate").toString()).getBytes("UTF-8")				        		
					        		);

					        if (!sig.verify( Base64.decodeBase64( getPostData.get("sign").toString().getBytes("UTF-8"))))
					        {
					        	
					        	 JSONObject GetError = new JSONObject();
					            	GetError.put("code", 201);
					            	GetError.put("msg", "Помилка перевірки підпису");
							 
						
									MakeSend (he, GetError.toString());
						
					        	
					        	return;
					        }
						
					        
					        SQLMake.MakeConnection();
							
					        SQLMake.AutoComit(false);
					        
					     
					        
					        getPostData.remove("sign");
					        
	
					       
					       
					       
					       JSONObject InsData = getPostData;
					       InsData.put("company_id", ((JSONObject)((JSONObject)Result.get("QueryData")).get(0)).get("company_id"));
					       
					       
					       
					       JSONObject GetTransacInfo =  SQLMake.main("GetTransacInfo", InsData, 1 );
					        
					       if (Globals.DEBUG)
				            	MakeDebug.Add("GetCurBal: " + GetTransacInfo);
					       
									       
					       if (!GetTransacInfo.get("result").equals("ok"))
					       {
								 
								 JSONObject GetError = new JSONObject();
					            	GetError.put("code", 209);
					            	GetError.put("msg", "Помилка отримання даних транзакції");
							 
					            	SQLMake.RollBack();
					            	SQLMake.ConnectionClose();
							
									MakeSend (he, GetError.toString());
							
							 
							 return;
							 }
					    	   
					       
					       if ( ((JSONObject)GetTransacInfo.get("QueryData"))==null )
					       {
					    	  
					    	   JSONObject GetError = new JSONObject();
				            	GetError.put("code", 210);
				            	GetError.put("msg", "Транзакція не знайдена");
						 
				            	SQLMake.RollBack();
				            	SQLMake.ConnectionClose();
						
								MakeSend (he, GetError.toString());
						
						 
						 return;
					    	   
					       }
					       
					       
					  
		
						
						JSONObject GetStatus = new JSONObject ();
						
						GetStatus.put("GetStatusByID", ((JSONObject)((JSONObject)GetTransacInfo.get("QueryData")).get(0)).get("TransferUID").toString());
						
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
								
								SQLMake.Commit();
								SQLMake.ConnectionClose();
								
								MakeSend (he, GetError.toString());
				            	
				            }
						
				            InsData.put("status_up", AnswGet.get("status"));
				            
				            
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
				            
							
						
							
							
							SQLMake.Commit();
							
							
							 SQLMake.AutoComit(true);
							SQLMake.ConnectionClose();
					        
					        
						
					        JSONObject Answer = new JSONObject();
					        Answer.put("code", 0);
					        Answer.put("info", new UPStatus().StatusInfo(AnswGet.get("status").toString()));
			            	
					 
					        
					        
					       
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
