package structures.HashTable;

import java.util.HashMap;
import java.util.Map;

import structures.common.Items;

public class HashTable {
	private  final Map<String, Items> hashTable=new HashMap<>();
	
	
    public void insert(String key, String keyString, String value) {
    	hashTable.put(key, new Items(key, keyString, value));
    }

    public Items search(String key) {
        return hashTable.get(key);
    }
}
