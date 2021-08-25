package com.ukr.pochta.ref;

import java.util.HashMap;
import java.util.Map;

public class UPStatus {
	
	private static Map<String, String> StatusInfo = new HashMap<String, String>();	
	
	static {
		StatusInfo.put ("DA", "запит на досилання одержано");
		StatusInfo.put ("DD", "досланий");
		StatusInfo.put ("DL", "досилається");
		StatusInfo.put ("DO", "для оплати");
		StatusInfo.put ("DP", "одержаний для повернення");
		StatusInfo.put ("EP", "оплачений");
		StatusInfo.put ("FN", "повід-ня про надходження/оплату/SMS/Email");
		StatusInfo.put ("FP", "Взаєморозрахунки по виплаченому переказу");
		StatusInfo.put ("MA", "службове повідомлення одержано");
		StatusInfo.put ("MS", "службове повідомлення");
		StatusInfo.put ("NO", "на оплату");
		StatusInfo.put ("OD", "одержаний після досилання");
		StatusInfo.put ("ON", "повідомлення про оплату невірне");
		StatusInfo.put ("OP", "оплачений");
		StatusInfo.put ("OZ", "оплата затримується");
		StatusInfo.put ("PD", "повернутий для оплати відправнику");
		StatusInfo.put ("PO", "в прийманні не значиться");
		StatusInfo.put ("TA", "Переказ одержано");
		StatusInfo.put ("TD", "досланий");
		StatusInfo.put ("TN", "повідомлення про оплату отримано");
		StatusInfo.put ("TV", "відправлений для повернення");
		StatusInfo.put ("UP", "одержувача повідомлено");
		StatusInfo.put ("VA", "запит на повернення одержано");
		StatusInfo.put ("VO", "відправлений для оплати");
		StatusInfo.put ("ZD", "запит на досилання");
		StatusInfo.put ("ZN", "в оплаті не значиться");
		StatusInfo.put ("ZO", "запит оплати");
		StatusInfo.put ("ZP", "запит переказу");
		StatusInfo.put ("ZR", "зареєстрований");
		StatusInfo.put ("ZV", "запит на повернення");

		}
	
	
	public String StatusInfo(String action) 
    { 
        return this.StatusInfo.get(action); 
    } 

}
