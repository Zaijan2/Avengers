#include <iostream> //Provides functionalities for input and output streams, such as cin, cout, and cerr.
#include <queue>//Defines the queue and priority_queue container adaptors for managing data in a FIFO (first-in, first-out) or priority order.
#include <vector>//implements the vector container, which is a dynamic array that can resize itself.
#include <algorithm>//Offers a variety of algorithms such as sorting, searching, and modifying containers.
#include <limits>//Defines numeric limits for various data types, such as the maximum and minimum values for integers or floating-point numbers.
using namespace std;

// Task 1: Insert magical creatures into the heap
/*The function add_creature adds a creature's power level to a max heap (priority queue),
 prints a message indicating the creature and its power level, and then displays the
 current state of the heap with the highest power creatures first.*/
void add_creature(priority_queue<int>& maxHeap, const string& creature, int power) {
    maxHeap.push(power);
    cout << "You added a " << creature << " with power level " << power << " to the heap!" << endl;

    cout << "Current heap (highest power creatures first): ";
    priority_queue<int> temp = maxHeap;
    while (!temp.empty()) {
        cout << temp.top() << " ";
        temp.pop();
    }
    cout << endl;
}

// Task 2: Remove the strongest creature
/*The function remove_strongest removes the creature with the highest power (the top element) from the max heap,
 prints a message indicating the strongest creature's power, and then displays the updated state of the heap
  with the remaining creatures. If the heap is empty, it prints a message indicating there are no creatures to remove.*/
void remove_strongest(priority_queue<int>& maxHeap) {
    if (maxHeap.empty()) {
        cout << "The heap is empty! No creatures to remove.\n";
        return;
    }

    int strongest = maxHeap.top();
    maxHeap.pop();
    cout << "You removed the strongest creature with power " << strongest << " from the heap!\n";

    cout << "Updated heap: ";
    priority_queue<int> temp = maxHeap;
    while (!temp.empty()) {
        cout << temp.top() << " ";
        temp.pop();
    }
    cout << endl;
}

// Task 3: Heapify a collection of creatures
/*The function heapify_creatures takes a vector of creature power levels and transforms it into either
a max heap or a min heap based on the isMaxHeap flag. If isMaxHeap is true,
it creates a max heap where the strongest creatures come first; if isMaxHeap is false,
it creates a min heap where the weakest creatures come first. It then prints the heap in the chosen order.*/
void heapify_creatures(vector<int>& powers, bool isMaxHeap) {
    if (isMaxHeap) {
        make_heap(powers.begin(), powers.end());  // Max-Heap
    } else {
        make_heap(powers.begin(), powers.end(), greater<int>());  // Min-Heap
    }

    // Display heap structure
    cout << (isMaxHeap ? "Max-Heap (strongest creatures first): " : "Min-Heap (weakest creatures first): ");

    if (isMaxHeap) {
        // For Max-Heap
        priority_queue<int> tempHeap(powers.begin(), powers.end());
        while (!tempHeap.empty()) {
            cout << tempHeap.top() << " ";
            tempHeap.pop();
        }
    } else {
        // For Min-Heap
        priority_queue<int, vector<int>, greater<int>> tempHeap(powers.begin(), powers.end(), greater<int>());
        while (!tempHeap.empty()) {
            cout << tempHeap.top() << " ";
            tempHeap.pop();
        }
    }
    cout << endl;
}


// Menu display and choice
//this is where the user would choose on the choices on what they want to see in the program
int get_valid_choice() {
    int choice;
    while (true) {
        cout << "\nChoose your next magical management task:\n";
        cout << "1. Add a magical creature to the heap\n";
        cout << "2. Remove the strongest creature from the heap\n";
        cout << "3. Organize a collection of creatures into a heap\n";
        cout << "Enter choice (1/2/3): ";

        cin >> choice;

        if (cin.fail() || choice < 1 || choice > 3) {
            cin.clear(); // Clear error flag
            cin.ignore(numeric_limits<streamsize>::max(), '\n'); // Ignore invalid input
            cout << "Invalid input! Please enter 1, 2, or 3.\n";
        } else {
            break;
        }
    }
    return choice;
}
/*this is where the program start*/
int main() {
    priority_queue<int> maxHeap;  // Max-Heap for managing magical creatures
    vector<string> creatureNames = {"Dragon", "Phoenix", "Unicorn", "Griffin", "Hydra"};
    cout<<"_______________________________________________________________________________"<<endl;
    cout << "Welcome to the Magical Creature Management Game!" << endl;
    cout << "In this game, you'll manage powerful creatures using heaps." << endl;
    cout<<"_______________________________________________________________________________"<<endl;

    while (true) {
        int choice = get_valid_choice();

        if (choice == 1) {
            // Task 1: Add a creature to the heap
            int creatureIndex = rand() % creatureNames.size();
            string creature = creatureNames[creatureIndex];
            int power;
            cout << "Enter the power level for the " << creature << ": ";
            cin >> power;
            add_creature(maxHeap, creature, power);

        } else if (choice == 2) {
            // Task 2: Remove the strongest creature
            remove_strongest(maxHeap);

        } else if (choice == 3) {
            // Task 3: Organize a collection of creatures into a heap
            int n;
            cout << "Enter the number of creatures to organize: ";
            cin >> n;

            vector<int> powers(n);
            cout << "Enter the power levels of the creatures: ";
            for (int i = 0; i < n; ++i) {
                cin >> powers[i];
            }

            cout << "Choose the type of heap:\n1. Max-Heap (strongest creatures first)\n2. Min-Heap (weakest creatures first)\n";
            int heapType;
            cin >> heapType;

            bool isMaxHeap = (heapType == 1);
            heapify_creatures(powers, isMaxHeap);
        }

        // Ask if the user wants to continue
        char continueGame;
        cout << "\nWould you like to continue managing your magical creatures? (y/n): ";
        cin >> continueGame;

        if (continueGame == 'n' || continueGame == 'N') {
            cout << "Thank you for playing the Magical Creature Management Game! Goodbye!\n";
            break;
        }
    }

    return 0;//the use of this is to run the code smoothly.
}
