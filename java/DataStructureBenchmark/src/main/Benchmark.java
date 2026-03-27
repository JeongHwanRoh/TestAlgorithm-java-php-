package main;


import java.io.BufferedReader;
import java.io.File;
import java.io.FileReader;
import java.io.FileWriter;
import java.io.IOException;
import java.util.ArrayList;
import java.util.Date;
import java.util.LinkedHashMap;
import java.util.List;
import java.util.Map;
import java.util.UUID;
import java.lang.management.ManagementFactory;
import java.lang.management.MemoryMXBean;
import java.lang.management.MemoryUsage;

import structures.Arraylist.Arraylist;
import structures.BTree.BTree;
import structures.Graph.Graph;
import structures.HashTable.HashTable;
import structures.Heap.Heap;
import structures.LinkedList.LinkedList;
import structures.Vector.Vector;

public class Benchmark {
    public static void main(String[] args) throws IOException {
        String rootDir = "C:/Users/jeonghwanroh/algorithmTest/java/DataStructureBenchmark";
        String dataFile = rootDir + "/data/dataset.txt";
        String resultFile = rootDir + "/results/benchmark_result.txt";
        int searchSampleSize = 1000;

        // 자료구조 인스턴스 생성 (클래스는 앞서 제공한 Java 버전 사용)
        HashTable hashTable = new HashTable();
        BTree bTree = new BTree(8);
        LinkedList linkedList = new LinkedList();
        Arraylist arrayList = new Arraylist();
        Vector vector = new Vector();
        Graph graph = new Graph();
        Heap heap = new Heap();

        // Generate data dynamically
        int dataCount = 200000; // 20 million entries
        List<String[]> data = generateData(dataCount);

        // Ensure the 'data' directory exists
        File dataDir = new File(rootDir + "/data");
        if (!dataDir.exists()) {
            dataDir.mkdirs();
        }

        // Save generated data to a file
        try (FileWriter fw = new FileWriter(dataFile)) {
            fw.write("key,keyString,value\n"); // Header
            for (String[] row : data) {
                fw.write(String.join(",", row) + "\n");
            }
        }
        System.out.println("Data generation completed: " + dataFile);

        // 샘플 검색 키 추출
        List<String> searchKeys = new ArrayList<>();
        for (int i = 0; i < Math.min(searchSampleSize, data.size()); i++) {
            searchKeys.add(data.get(i)[0]);
        }

        // 벤치마크 실행
        Map<String, Object> structures = new LinkedHashMap<>();
        structures.put("HashTable", hashTable);
        structures.put("BTree", bTree);
        structures.put("LinkedList", linkedList);
        structures.put("ArrayList", arrayList);
        structures.put("Vector", vector);
        structures.put("Graph", graph);
        structures.put("Heap", heap);

        // Ensure the 'results' directory exists
        File resultsDir = new File(rootDir + "/results");
        if (!resultsDir.exists()) {
            resultsDir.mkdirs();
        }

        MemoryMXBean memoryBean = ManagementFactory.getMemoryMXBean();

        StringBuilder output = new StringBuilder();
        output.append("Benchmark Started At: ").append(new Date()).append("\n");
        output.append("Data File: ").append(dataFile).append("\n");
        output.append("Search Sample Size: ").append(searchKeys.size()).append("\n\n");

        for (Map.Entry<String, Object> entry : structures.entrySet()) {
            String name = entry.getKey();
            Object structure = entry.getValue();
            try {
                long insertStart = System.currentTimeMillis();
                for (String[] row : data) {
                    if (structure instanceof HashTable)
                        ((HashTable) structure).insert(row[0], row[1], row[2]);
                    else if (structure instanceof BTree)
                        ((BTree) structure).insert(row[0], row[1], row[2]);
                    else if (structure instanceof LinkedList)
                        ((LinkedList) structure).insert(row[0], row[1], row[2]);
                    else if (structure instanceof Arraylist)
                        ((Arraylist) structure).insert(row[0], row[1], row[2]);
                    else if (structure instanceof Vector)
                        ((Vector) structure).insert(row[0], row[1], row[2]);
                    else if (structure instanceof Graph)
                        ((Graph) structure).insert(row[0], row[1], row[2]);
                    else if (structure instanceof Heap)
                        ((Heap) structure).insert(row[0], row[1], row[2]);
                }
                long insertEnd = System.currentTimeMillis();
                long insertTime = insertEnd - insertStart;

                MemoryUsage heapMemoryUsage = memoryBean.getHeapMemoryUsage();
                long peakMemory = heapMemoryUsage.getUsed() / (1024 * 1024);

                long sortStart = System.currentTimeMillis();
                if (structure instanceof Arraylist)
                    ((Arraylist) structure).sort();
                else if (structure instanceof Vector)
                    ((Vector) structure).sort();
                else if (structure instanceof LinkedList)
                    ((LinkedList) structure).sort();
                long sortEnd = System.currentTimeMillis();
                long sortTime = sortEnd - sortStart;

                long searchStart = System.currentTimeMillis();
                int found = 0;
                for (String key : searchKeys) {
                    Object result = null;
                    if (structure instanceof HashTable)
                        result = ((HashTable) structure).search(key);
                    else if (structure instanceof BTree)
                        result = ((BTree) structure).search(key);
                    else if (structure instanceof LinkedList)
                        result = ((LinkedList) structure).search(key);
                    else if (structure instanceof Arraylist)
                        result = ((Arraylist) structure).search(key);
                    else if (structure instanceof Vector)
                        result = ((Vector) structure).search(key);
                    else if (structure instanceof Graph)
                        result = ((Graph) structure).search(key);
                    else if (structure instanceof Heap)
                        result = ((Heap) structure).search(key);
                    if (result != null) found++;
                }
                long searchEnd = System.currentTimeMillis();
                long searchTime = searchEnd - searchStart;

                output.append("========== ").append(name).append(" ==========").append("\n");
                output.append("Inserted Count     : ").append(data.size()).append("\n");
                output.append("Insert Time (ms)   : ").append(insertTime).append("\n");
                output.append("Search Sample      : ").append(searchKeys.size()).append("\n");
                output.append("Found Count        : ").append(found).append("\n");
                output.append("Search Time (ms)   : ").append(searchTime).append("\n");
                output.append("Sort Time (ms)     : ").append(sortTime).append("\n");
                output.append("Peak Memory (MB)   : ").append(peakMemory).append("\n\n");

                // Write partial results to file
                try (FileWriter fw = new FileWriter(resultFile, true)) {
                    fw.write(output.toString());
                }
                output.setLength(0); // Clear the buffer

                System.out.println(name + " benchmark completed.");
            } catch (Exception e) {
                System.err.println("Error during " + name + " benchmark: " + e.getMessage());
            }
        }
    }

    private static List<String[]> generateData(int count) {
        List<String[]> data = new ArrayList<>();
        for (int i = 0; i < count; i++) {
            String key = UUID.randomUUID().toString();
            String keyString = "KeyString_" + i;
            String value = "Value_" + UUID.randomUUID().toString();
            data.add(new String[]{key, keyString, value});
        }
        return data;
    }
}