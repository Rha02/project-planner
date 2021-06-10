class LinkedList {
    constructor() {
        this.head = null
        this.length = 0
    }

    add(elem) {
        var node = new Node(elem)

        var current

        if (this.head == null) {
            this.head = node
        } else {
            current = this.head

            while (current.next) {
                current = current.next
            }

            current.head = node
        }

        this.length++
    }

    insert(elem, idx) {
        var node = new Node(elem)

        var current, prev

        current = this.head

        var counter = 0

        while (counter < idx) {
            counter++
            prev = current
            current = current.next
        }

        current.head = node
        prev.next = node

        this.length++
    }

    remove(idx) {
        var current, prev

        current = this.head

        while (counter < idx) {
            counter++
            prev = current
            current = current.next
        }
        
        prev.next = current.next
    }
}

class Node {
    constructor(data) {
        this.data = data
        this.next = null
    }
}