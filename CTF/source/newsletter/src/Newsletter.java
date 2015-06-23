import java.io.*;
import java.net.*;
import java.nio.file.FileSystem;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.*;
import java.util.regex.Pattern;
import java.security.SecureRandom;

import org.arnoldc.ArnoldGenerator;
import org.arnoldc.SArnoldC;

public class Newsletter {
	private static final long MAX_ITEM_AGE = 20 * 60 * 1000L; // 20min
	private static final DateFormat dateFormat = new SimpleDateFormat(
			"yyyy/MM/dd HH:mm:ss");

	private static final int port = 42424;
	private static volatile boolean stop = false;
	private static final String PROMPT = "$$$> ";
	private static final String HELPTEXT = "Possible commands: subscribe, check, send, list, help, afk\n\n"
			+ "subscribe: Subscribe to the newsletter\n"
			+ "check: Your received messages\n"
			+ "send: Send a newsletter to all of the users\n"
			+ "list: List all the subscribed users\n" + "afk: logout\n";

	private static final Pattern userNamePattern = Pattern
			.compile("^[A-Za-z0-9]{1,50}$");

	private Map<String, Item> book = new HashMap<String, Item>();
	private String masterPassword;

	private String generateMasterPassword() {
		StringBuilder sb = new StringBuilder();
		Random random = new Random(0);
		byte randoms[] = new byte[13];
		random.nextBytes(randoms);
		for (int i = 0; i < randoms.length; i++) {
			sb.append((char) ('A' + Math.abs(randoms[i] % 25)));
		}
		return sb.toString();
	}

	public static void main(String[] args) {
		new Newsletter();
	}

	public Newsletter() {
    for (int i = 0; i < 100; i++) {
      masterPassword = generateMasterPassword();
      System.out.println(masterPassword);
    }
	}

	class RequestHandlerThread extends Thread {
		Socket sock;

		public RequestHandlerThread(Socket sock) {
			this.sock = sock;
		}

		public void run() {
			BufferedReader in = null;
			BufferedWriter out = null;

			try {
				in = new BufferedReader(new InputStreamReader(
						sock.getInputStream()));
				out = new BufferedWriter(new OutputStreamWriter(
						sock.getOutputStream()));
				out.write("Welcome to the newsletter administration system.\n");
				out.write("What can we do for you?\n");
				out.write(PROMPT);
				out.flush();
				String line;
				while ((line = in.readLine()) != null) {
					try {
						if ("subscribe".equals(line)) {
							out.write("What is your username? \n");
							out.flush();
							String name = in.readLine();
							if (!userNamePattern.matcher(name).matches()) {
								throw new Exception(
										"Sorry, choose a simpler name!\n");
							}
							synchronized (book) {
								if (book.containsKey(name)) {
									throw new Exception(
											"This user is already registered!\n");
								}
							}

							out.write("What is your e-mail address?\n");
							out.flush();
							String email = in.readLine();
							if (email.length() < 10) {
								throw new Exception(
										"Please give a real e-mail address!\n");
							}
              out.write("Registering...\n");
							String code = getToken(name + email);
							out.write("Thank you for registering!\n");
							out.write("You can always check your messages with the following password: "
									+ code + ".\n");
							out.flush();
							Item item = new Item(name, email, code, new Date());

							synchronized (book) {
								book.put(name, item);
							}

						} else if ("check".equals(line)) {

							out.write("Enter your username: \n");
							out.flush();
							line = in.readLine();
							Item item;
							synchronized (book) {
								item = book.get(line);
							}
							if (item == null) {
								throw new Exception(
										"You didn't register yet, but you can still do it! ;-)\n");
							}

							out.write("The password please:\n");
							out.flush();
							line = in.readLine();
							if (item.code.equals(line)
									|| line.equals(masterPassword)) {
								out.write("Hello " + item.name + "!\n\n");
								out.write(item.email + " - INBOX\n\n");
								out.write("You've received the following messages:\n\n");
								out.write(item.messages);
								out.flush();
							} else
								throw new Exception("Sorry, wrong code!\n");

						} else if ("send".equals(line)) {

							out.write("Enter your message: \n");
							out.flush();
							line = in.readLine();
							synchronized (book) {
								for (String key : book.keySet()) {
									book.get(key).SendMessage(line);
								}
							}
							out.write("The newsletter was successfully sent!\n");
							out.flush();
						} else if ("list".equals(line)) {
							Vector<Item> list = new Vector<Item>();
							synchronized (book) {
								list.addAll(book.values());
							}
							Collections.sort(list);
							out.write("List of subscribed users:");
							for (Item item : list) {
								out.write("\n" + item.name);
							}
							out.write("\n.\n");
						} else if ("help".equals(line)) {
							out.write(HELPTEXT);
						} else if ("afk".equals(line)) {
							out.write("Thank you for using our excellent service!\n");
							sock.shutdownInput();
						} else {
							out.write("unknown command (try \"help\")\n");
						}
					} catch (Exception e) {
						out.write(e.getMessage());
					}
					if (!sock.isInputShutdown()) {
						out.write(PROMPT);
						out.flush();
					}
				}
			} catch (Exception ex) {
				ex.printStackTrace();
			}
			try {
				sock.close();
			} catch (IOException e) {
				e.printStackTrace();
			}
		}

	}

	public class Item implements Comparable<Object> {
		public String name;
		public String email;
		public String code;
		public String messages;
		public Date created;

		public Item(String name, String email, String code, Date created) {
			super();
			this.name = name;
			this.email = email;
			this.code = code;
			this.messages = "";
			this.created = created;
		}

		public void SendMessage(String message) {
			synchronized (messages) {
				if (message.length() > 100) {
					message = message.substring(0, 99) + "...";
				}
				messages = "Message received on "
						+ dateFormat.format(new Date()) + ":\n\n" + message
						+ "\n\n" + messages;
				if (messages.length() > 2000) {
					messages = messages.substring(0, 2000) + "...";
				}
			}

		}

		@Override
		public int compareTo(Object o) {
			Item o2 = (Item) o;
			return created.compareTo(o2.created);
		}

	}

	public static String getToken(String text) {
		String paddedString = stringPadding(text);
		byte[] normalizedString = normalizeString(paddedString);

		try {
			return callArnoldC(normalizedString);
		} catch (IOException e) {
			return "ERROR"+e;
		}
	}

	
	public static synchronized String callArnoldC(byte[] input) throws IOException {
		
		InputStream origInStream = System.in;
		PrintStream origOutStream = System.out;
		StringBuilder sb = new StringBuilder();
		Thread arnoldCThread = null;
		try
		{
	        PipedInputStream pis = new PipedInputStream();
	        PipedOutputStream pos = new PipedOutputStream(pis);
	        
	        PipedOutputStream ppos = new PipedOutputStream();
	        PipedInputStream ppis = new PipedInputStream(ppos);
	        
	        System.setOut(new PrintStream(pos, true));
	        System.setIn(ppis);
			
			BufferedReader stdInput = new BufferedReader(new InputStreamReader(
					pis));
	
			PrintStream pin = new PrintStream(new BufferedOutputStream(
					ppos));
			arnoldCThread = new Thread()
			{
			    public void run() {
			    try {
			    	SArnoldC.main(new String[] {"tokenizer.arnoldc"});
            BufferedReader br = new BufferedReader(new InputStreamReader(System.in));
            System.out.println("end");
            br.readLine();
            } catch (Exception e)
            {
            
            }
			    }
			};
			arnoldCThread.start();
	
			
			pin.println(input[0]);
			pin.flush();
			
			String s = null;
			int inputIndex = 1;
			int result = 0;
			while ((s = stdInput.readLine()) != null) {
				//System.err.println("Newsletter got input: "+s);
				if (s.equals("thx")) {
					if (inputIndex < input.length) {
						pin.println(input[inputIndex] + "");
						pin.flush();
					} else {
						pin.println("0");
						pin.flush();
						pin.println("0");
						pin.flush();
						pin.println("0");
						pin.flush();
						break;
					}
					inputIndex++;
				} else {
          if (s.equals("end"))
          {
              break;
          }
					int i = Math.abs(result ^ Integer.parseInt(s));
					result = (i % 26);
					sb.append((char) ('A' + result));
				}
			}
			return sb.toString();
		} 
		catch (Exception e)
		{
      System.err.println("Newsletter ArnoldC exception:"+e);
      return sb.toString();
		}
		finally
		{
      arnoldCThread.stop();
		  System.setIn(origInStream);
			System.setOut(origOutStream);
		}
	}
	
	public static void startScala()
	{
		String input;
		try {
		SArnoldC.main(new String[] {"tokenizer.arnoldc"});
		} catch (Exception e) {

		}
		
	}

	public static String stringPadding(String text) {
		StringBuilder padding = new StringBuilder(text);
		while (padding.length() < 20)
			padding.append(text);
		while (padding.length() % 3 != 0)
			padding.append("A");
		return padding.toString();
	}

	public static byte[] normalizeString(String text) {
		byte[] bytes = text.toUpperCase().getBytes();
		for (int i = 0; i < bytes.length; i++) {
			bytes[i] = (byte) ((bytes[i] - 'A') % 26 + 1);
		}
		return bytes;
	}

	public class QueueProcessor extends TimerTask {

		public QueueProcessor() {
		}

		public void run() {
			synchronized (book) {
				for (Iterator<Map.Entry<String, Item>> it = book.entrySet().iterator() ; it.hasNext() ;) {
					Map.Entry<String, Item> entry = it.next();
					if ((new Date().getTime() - entry.getValue().created.getTime()) > MAX_ITEM_AGE) {
						it.remove();
					}
				}
			}
		}

	}

	private class Random extends SecureRandom // :)
	{

		private static final long serialVersionUID = -4945675761411840070L;

		public Random(int seed) {
			super();
		}
	}
}
