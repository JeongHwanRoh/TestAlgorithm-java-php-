package structures.Arraylist;

import java.util.ArrayList;
import java.util.Comparator;
import java.util.List;

import structures.common.Items;

public class Arraylist {

	private final List<Items> items = new ArrayList<>();
	
	// 시간복잡도: O(1)
	public void insert(String key, String keyString, String value) {
		items.add(new Items(key, keyString, value));
	}
	
	// 시간복잡도: O(n)
	public Items search(String key) {
		for (Items item : items) {
			if (item.key.equals(key)) {
				return item;
			}
		}
		return null;
	}
	
	// sort 알고리즘: Timsort (Java 7 이상에서 사용되는 안정적인 정렬 알고리즘)
	// merge sort(nlogn) + insertion sort(n) => O(n log n)
	// 시간 복잡도: O(n log n) 
	public void sortByKeyString() {
		items.sort(Comparator.comparing(i -> i.keyString));
	}
	
	public void sort() {
		items.sort(Comparator.comparing(i -> i.key));
	}
	
	// 시간복잡도: O(1)
	public int count() {
		return items.size();
	}
}