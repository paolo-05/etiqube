import random
import sys

states = ['vuoto', 'pieno']

state = random.choice(states)

print(f"Lo sportello numero {sys.argv[1]} è {state}.")