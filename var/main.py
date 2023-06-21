import random
import sys

def main():

    states = ['vuoto', 'pieno']

    state = random.choice(states)

    if(len(sys.argv) == 1):
        return print("Numero dello sportello richiesto come argomento nella riga di comando.")
    if(len(sys.argv) > 2):
        return print("Troppi argomenti")

    print(f"Lo sportello numero {sys.argv[1]} è {state}.")


if __name__ == '__main__':
    main()