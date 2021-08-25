package com.ukr.pochta.ref;

import java.util.HashMap;
import java.util.Map;

public class UPStatus {
	
	private static Map<String, String> StatusInfo = new HashMap<String, String>();	
	
	static {
		StatusInfo.put ("DA", "����� �� ��������� ��������");
		StatusInfo.put ("DD", "��������");
		StatusInfo.put ("DL", "����������");
		StatusInfo.put ("DO", "��� ������");
		StatusInfo.put ("DP", "��������� ��� ����������");
		StatusInfo.put ("EP", "���������");
		StatusInfo.put ("FN", "����-�� ��� �����������/������/SMS/Email");
		StatusInfo.put ("FP", "��������������� �� ����������� ��������");
		StatusInfo.put ("MA", "�������� ����������� ��������");
		StatusInfo.put ("MS", "�������� �����������");
		StatusInfo.put ("NO", "�� ������");
		StatusInfo.put ("OD", "��������� ���� ���������");
		StatusInfo.put ("ON", "����������� ��� ������ ������");
		StatusInfo.put ("OP", "���������");
		StatusInfo.put ("OZ", "������ �����������");
		StatusInfo.put ("PD", "���������� ��� ������ ����������");
		StatusInfo.put ("PO", "� �������� �� ���������");
		StatusInfo.put ("TA", "������� ��������");
		StatusInfo.put ("TD", "��������");
		StatusInfo.put ("TN", "����������� ��� ������ ��������");
		StatusInfo.put ("TV", "����������� ��� ����������");
		StatusInfo.put ("UP", "���������� ����������");
		StatusInfo.put ("VA", "����� �� ���������� ��������");
		StatusInfo.put ("VO", "����������� ��� ������");
		StatusInfo.put ("ZD", "����� �� ���������");
		StatusInfo.put ("ZN", "� ����� �� ���������");
		StatusInfo.put ("ZO", "����� ������");
		StatusInfo.put ("ZP", "����� ��������");
		StatusInfo.put ("ZR", "�������������");
		StatusInfo.put ("ZV", "����� �� ����������");

		}
	
	
	public String StatusInfo(String action) 
    { 
        return this.StatusInfo.get(action); 
    } 

}
