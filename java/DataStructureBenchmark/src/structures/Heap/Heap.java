package structures.Heap;

import java.util.PriorityQueue;

import structures.common.HeapItems;

public class Heap {
	private final PriorityQueue<HeapItems> heap=new PriorityQueue<>();

	public void insert(String key, String keyString, String value) {
		heap.add(new HeapItems(key, keyString, value));
	}
	public HeapItems search(String key) {
		for (HeapItems item : heap) {
			if (item.key.equals(key))
				return item;
		}
		return null;
	}
	public HeapItems extractMin() {
		return heap.poll();
	}
}
