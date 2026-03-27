package structures.Graph;

import java.util.ArrayList;
import java.util.Collections;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import structures.common.Items;

public class Graph {
	// 해시맵 기반의 인접 리스트로 구현
	private final Map<String, Items> vertices = new HashMap<>(); // 각 정점의 키와 관련된 데이터를 저장하는 해시맵
	private final Map<String, List<String>> adjList = new HashMap<>(); // 각 정점의 키와 인접한 정점들의 리스트를 저장하는 해시맵(인접 리스트의 변형 형태)
	private final Map<String, String> lastByPrefix = new HashMap<>(); // 각 접두사에 대해 마지막으로 삽입된 정점의 키를 저장하는 해시맵

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
