import random
import sys

def main():

    lc = []
    ir = []

    for i in range(1, 10):
        lc.append(i+1)

        condition = random.choice([True, False])

        if condition:
            ir.append(i+1)

    if(len(sys.argv) == 1):
        return print("Numero della scheda richiesto come argomento nella riga di comando.")

    response = {"cu": sys.argv[1], "lc": [i for i in lc], "ir": [i for i in ir]}
    
    print(response)
    
if __name__ == '__main__':
    main()