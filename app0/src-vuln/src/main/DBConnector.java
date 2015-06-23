package main;
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;

public class DBConnector
{
	public static void main(String[] args)
	{
		new DBConnector();
	}
	
	public DBConnector()
    {
          
	    try
	    {
	      Class.forName( "org.hsqldb.jdbcDriver" ); 
	    } 
	    catch ( ClassNotFoundException e ) 
	    { 
	      System.err.println( "Treiberklasse nicht gefunden!" ); 
	      return; 
	    } 
	  
	    Connection con = null; 
	  
	    try
	    { 
	      con = DriverManager.getConnection(  
	              "jdbc:hsqldb:file:home; shutdown=true", "SA", "" ); 
	      Statement stmt = con.createStatement();
	      
	      boolean logged = false;
	      String name = "hugo";
	      String pw = "pw123";
	      String sql = "";
	      ResultSet rs;
	      
	      BufferedReader br = new BufferedReader(new InputStreamReader(System.in));
	      
	      while(!logged)
	      {
	    	  name = "";
	    	  pw = "";
	    	  System.out.println("Benutzername eingeben:");
	    	  name = br.readLine();
	    	  System.out.println("Passwort eingeben:");
	    	  pw = br.readLine();
	    	  
	    	  //VULNERABLE
	    	  sql = "select * from user where name='"+name+"' and password='"+pw+"';";
	    	  try{
	    		  rs = stmt.executeQuery(sql);
	    	  }catch(SQLException e){
	    		  System.out.println("Ungültige Eingabe, bitte versuchen Sie es erneut!");
	    		  continue;
	    	  }
	    	  //VULNERABLE
	    	  
	    	  if(rs.next())
	    		  logged = true;
	    	  else
	    		  System.out.println("Ungültige Eingabe, bitte versuchen Sie es erneut!");
	    	  
	    	  rs.close();
	      }
	      System.out.println("Sie sind nun erfolgreich eingeloggt als "+name+"! Herzlich Willkommen!");
	      
	      stmt.close();
	    }
	    catch ( SQLException e ) 
	    { 
	      e.printStackTrace(); 
	    }
	    catch ( IOException e )
	    {
	    	e.printStackTrace();
	    }
	    finally
	    { 
	      if ( con != null ) 
	      {
	        try { 
	            con.close(); 
	            } catch ( SQLException e ) { 
	                e.printStackTrace(); 
	            }
	      }
	    }
    }
}
