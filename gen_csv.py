import sys
from os import listdir
from os.path import isfile, join, abspath, basename

def print_row(abs_path):
  print "%s,%s" % (basename(abs_path), abs_path.replace('/', ':'))

if __name__ == "__main__":
  _, path, ext, back, rules1, rules2, rules3 = sys.argv
  card_front_abs_paths = []
  for f in listdir(path):
    abs_path = abspath(join(path, f))
    if isfile(abs_path) and f.endswith("." + ext):
      card_front_abs_paths.append(abs_path)

  card_faces = [back, rules1, rules2, rules3]
  for path in sorted(card_front_abs_paths):
    card_faces.append(back)
    card_faces.append("Macintosh HD" + path)

  print "Name,@images"
  for card_face in card_faces:
    print_row(card_face)