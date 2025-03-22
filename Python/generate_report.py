import sys
import json

def generate_report(data):
    report = f"Report generated with the following data:\n{json.dumps(data, indent=4)}"
    return report

if __name__ == "__main__":
    input_data = json.loads(sys.argv[1])
    report = generate_report(input_data)
    print(report)