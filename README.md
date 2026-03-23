# Data Structure Benchmark (PHP vs Java)

## 📌 개요

본 프로젝트는 다양한 자료구조(HashTable, B-Tree, LinkedList, ArrayList, Vector, Graph, Heap)의  
**삽입(Insert), 탐색(Search), 정렬(Sort), 메모리 사용량**을 비교하기 위한 벤치마크입니다.

또한 PHP와 Java 두 언어에서 동일한 구조를 구현하여  
**언어별 성능 차이와 자료구조 특성**을 분석합니다.

---

## 🧪 실험 환경

### 📂 데이터
- 데이터 수: **200,000건**
- 구조:


---

### ⚙️ 실행 환경

| 항목 | PHP | Java |
|------|-----|------|
| 메모리 제한 | 1024MB | JVM 기본 |
| 데이터 구조 | PHP Array 기반 | Java Collection / Custom |
| GC | 자동 (느슨함) | 자동 (정교함) |

---

## 📊 Benchmark 결과

### 🔹 PHP 결과

| Structure   | Insert(ms) | Search(ms) | Sort(ms) | Memory(MB) |
|------------|-----------|-----------|----------|-----------|
| HashTable  | 785       | 0.254     | 623      | 136       |
| BTree      | 1963      | 5.073     | -        | 246       |
| LinkedList | 768       | 11.611    | 902      | 404       |
| ArrayList  | 770       | 13.471    | 705      | 446       |
| Vector     | 758       | 20.376    | 689      | 624       |
| Graph      | 931       | 0.345     | 744      | 730       |
| Heap       | 858       | 0.179     | 858      | 862       |

---

### 🔹 Java 결과

| Structure   | Insert(ms) | Search(ms) | Sort(ms) | Memory(MB) |
|------------|-----------|-----------|----------|-----------|
| HashTable  | 57        | 0         | 0        | 71        |
| BTree      | 321       | 3         | -        | 83        |
| LinkedList | 9         | 7489      | 522613   | 87        |
| ArrayList  | 26        | 4631      | 185      | 99        |
| Vector     | 36        | 4492      | 190      | 107       |
| Graph      | 133       | 1         | -        | 147       |
| Heap       | 89        | 4435      | -        | 140       |

---

## 📈 결과 분석

### 1️⃣ 삽입 성능 (Insert)

- Java가 전반적으로 PHP보다 빠른 성능을 보임
- 이유:
  - 연속 메모리 구조
  - 낮은 객체 오버헤드
- B-Tree는 노드 분할로 인해 가장 느림

📌 결론:
> Java > PHP (Insert 성능)

---

### 2️⃣ 탐색 성능 (Search)

- HashTable은 두 언어 모두 매우 빠름 (O(1))
- LinkedList, ArrayList, Vector는 매우 느림 (O(n))
- Heap은 탐색 비효율적 구조

📌 핵심:
> 탐색 성능은 **언어가 아니라 자료구조에 의해 결정됨**

---

### 3️⃣ 정렬 성능 (Sort)

- Array 기반 구조(ArrayList, Vector)가 가장 효율적
- LinkedList는 매우 비효율적
- Heap은 구조 특성상 정렬 비용 큼

📌 결론:
> 배열 기반 구조 > 연결 구조

---

### 4️⃣ 메모리 사용량

- PHP는 매우 높은 메모리 사용량
- Java는 훨씬 효율적

📌 이유:
- PHP 배열 = HashTable 기반
- zval + bucket + pointer 오버헤드
- GC 지연

📌 결론:
> PHP >> Java (메모리 사용량)

---

## ⚠️ 주요 이슈 및 한계

### PHP의 구조적 한계

- 배열 over-allocation
- 객체 오버헤드
- GC 지연
- 동일 데이터 중복 저장 (Heap, Graph)

📌 결과:
> 실제 데이터보다 3~5배 메모리 사용

---

### 대용량 데이터 한계

| 데이터 수 | 상태 |
|----------|------|
| 200k | 안정 |
| 500k | 구조 제한 필요 |
| 1M | PHP에서 비추천 |

---

## 🧠 핵심 인사이트

### 🔥 가장 중요한 결론

> 자료구조 성능은 언어보다 **구조 자체**에 의해 결정된다.

---

### 🔍 추가 인사이트

- HashTable은 실전에서 가장 강력한 구조
- LinkedList는 탐색 성능이 매우 나쁨
- Heap과 Graph는 특정 목적에 적합
- 언어에 따라 성능 해석이 달라질 수 있음

---

## 🏁 최종 결론

- Java는 이론적인 성능에 가까운 결과
- PHP는 내부 구조로 인해 성능 왜곡 발생
- 자료구조 선택이 성능에 가장 큰 영향을 미침

---

## 📌 향후 개선 방향

- C++/Rust 기반 비교 실험
- 메모리 최적화 구조 설계
- 대용량 데이터 스트리밍 처리
- 병렬 처리 벤치마크

---

## 👨‍💻 Author

- Name: Jeonghwan Roh
- Project: Data Structure Benchmark
