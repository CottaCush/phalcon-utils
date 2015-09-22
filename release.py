import sys
import getopt


class Release:

    def __init__(self):
        pass

    @staticmethod
    def get_args():
        arguments = sys.argv[1:]
        try:
            options = getopt.getopt(arguments, "hv:", ["--version=", "help"])
            return options
        except getopt.GetoptError:
            print "Error getting options"



release = Release()
options = release.get_args()




