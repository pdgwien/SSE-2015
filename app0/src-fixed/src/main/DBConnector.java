package main;
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;

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
	    	  
	    	  
	    	  //SAFE
	    	  sql = "select * from user where name = ? and password = ? ;";
	    	  PreparedStatement ps = con.prepareStatement(sql);
	    	  ps.setString(1, name);
	    	  ps.setString(2, pw);
	    	  
	    	  try{
	    		  rs = ps.executeQuery();
	    	  }catch(SQLException e){
	    		  System.out.println("Ungültige Eingabe, bitte versuchen Sie es erneut!");
	    		  continue;
	    	  }
	    	  //SAFE
	    	  
	    	  
	    	  if(rs.next())
	    		  logged = true;
	    	  else
	    		  System.out.println("Ungültige Eingabe, bitte versuchen Sie es erneut!");
	    	  
	    	  rs.close();
	      }
	      System.out.println("Sie sind nun erfolgreich eingeloggt als "+name+"! Herzlich Willkommen!");
	      
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
