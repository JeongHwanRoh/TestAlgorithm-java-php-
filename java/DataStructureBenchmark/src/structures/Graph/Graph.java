package structures.Graph;

import java.util.ArrayList;
import java.util.Collections;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import structures.common.Items;

public class Graph {
	private final Map<String, Items> vertices = new HashMap<>();
	private final Map<String, List<String>> adjList = new HashMap<>();
	private final Map<String, String> lastByPrefix = new HashMap<>();

	// insert
	public void insert(String key, String keyString, String value) {
		vertices.put(key, new Items(key, keyString, value));
		adjList.putIfAbsent(key, new ArrayList<>());

		String prefix = keyString.contains("-") ? keyString.split("-")[0] : "default";

		if (lastByPrefix.containsKey(prefix)) {
			String prevKey = lastByPrefix.get(prefix);
			adjList.get(prevKey).add(key);
			adjList.get(key).add(prevKey);
		}

		lastByPrefix.put(prefix, key);
	}

	// search	
	public Items search(String key) {
		return vertices.get(key);
	}

}
