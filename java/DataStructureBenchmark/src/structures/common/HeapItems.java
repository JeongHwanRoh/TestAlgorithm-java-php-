package structures.common;

public class HeapItems implements Comparable<HeapItems> {
	public String key;
	public String keyString;
	public String value;
	
	public HeapItems(String key, String KeyString, String value) {
		this.key = key;
		this.keyString = KeyString;
		this.value = value;
	}

	@Override
	public int compareTo(HeapItems o) {
		return this.key.compareTo(o.key);
	}

}
